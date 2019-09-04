<?php 
namespace App\Traits;

use PDF;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser{
	private function successResponse($data, $code){
		
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code){
		
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function showAll(Collection $collection, $code = 200){

		if($collection->isEmpty()){
			return $this->successResponse(['data' => $collection], $code);
		}

		$transformer = $collection->first()->transformer;
		$collection = $this->filterData($collection, $transformer);
		$collection = $this->sortData($collection, $transformer);
		//$collection = $this->paginate($collection);
		$collection = $this->transformData($collection, $transformer);
		//$collection = $this->cacheResponse($collection);

		return $this->successResponse($collection, $code);
	}

	protected function showAllToPDF(Collection $collection, $collectName, $view, $outputName = "archivo", $extraData = null){

		if($collection->isEmpty()){
			$collection = null;
		}
		else{
			$transformer = $collection->first()->transformer;
			$collection = $this->filterData($collection, $transformer);
			$collection = $this->sortData($collection, $transformer);
			//$collection = $this->paginate($collection);
			$collection = $this->transformData($collection, $transformer);
			//$collection = $this->cacheResponse($collection);		
		}

        $pdf = PDF::loadView($view, [
			$collectName => isset($collection) ? $collection['data'] : $collection, 
			'datosExtra' => $extraData
		]);
        
        return $pdf->download($outputName."pdf");
	}


	protected function showOne(Model $instance, $code = 200){
		
		$transformer = $instance->transformer;

		$instance = $this->transformData($instance, $transformer);
		
		return $this->successResponse($instance, $code);
	}

	protected function showMessage($message, $code = 200){
		
		return $this->successResponse(['data' => $message], $code);
	}

	protected function filterData(Collection $collection, $transformer){
		
		foreach (request()->query() as $query => $value) {
			$diferente_de = false;

			if(strlen($query) > 1 && $query[0] == "!"){
				$query = substr($query, 1);
				$diferente_de = true;
			}

			$attribute = $transformer::originalAttribute($query);
			if(isset($attribute, $value)){

				$value == "null" ? $value = null : $value;

				if($diferente_de){
					$collection = $collection->where($attribute, '!=' ,$value);
				}
				else{
					$collection = $collection->where($attribute, $value);	
				}
			}
		}

		return $collection;
	}

	protected function sortData(Collection $collection, $transformer){

		if(request()->has('sortBy')){
			$attribute = $transformer::originalAttribute(request()->sortBy);
	
			//$collection = $collection->sortBy->{$attribute};
			$collection = $collection->sortBy($attribute);
		}
		else if(request()->has('sortByDesc')){
			$attribute = $transformer::originalAttribute(request()->sortByDesc);

			$collection = $collection->sortByDesc($attribute);
		}

		return $collection;
	}

	protected function paginate($collection){

		$rules = [
			'per_page' => 'integer|min:2|max:50',
		];

		Validator::validate(request()->all(), $rules);

		$page = LengthAwarePaginator::resolveCurrentPage();

		$perPage = 50;

		if(request()->has('per_page')){
			$perPage = (int) request()->per_page;
		}

		$results = $collection->slice( ($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page, [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());

		return $paginated;

	}

	protected function transformData($data, $transformer){

		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();
	}

	protected function cacheResponse($data){

		$url = request()->url();
		$queryParams= request()->query();

		ksort($queryParams);

		$queryString = http_build_query($queryParams);

		$fullUrl = "{url}?{$queryString}";

		return Cache::remember($fullUrl, 15/60, function() use($data){
			return $data;
		});
	}

}

?>