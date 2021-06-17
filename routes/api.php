<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::group(['prefix'=>'sc'], function() {
    Route::get('getShards', 'Api\SolrCloudApiController@getShards');
    Route::get('getNodeInfo', 'Api\SolrCloudApiController@getNodeInfo');
    Route::get('getNodes', 'Api\SolrCloudApiController@getNodes');
    Route::get('getCollectionOps', 'Api\SolrCloudApiController@getCollectionOps');
    Route::get('getCollectionOpsErrors', 'Api\SolrCloudApiController@getCollectionOpsErrors');
    Route::get('getRunningMap', 'Api\SolrCloudApiController@getRunningMap');
    Route::get('getRequestStatus/{asyncId}', 'Api\SolrCloudApiController@getRequestStatus');
    Route::get('getState', 'Api\SolrCloudApiController@getState');
    Route::get('getShardGraph', 'Api\SolrCloudApiController@getShardGraph');
    Route::get('getShardCountUsageDisk', 'Api\SolrCloudApiController@getShardCountUsageDisk');
    Route::get('getReplicaSplitShard', 'Api\SolrCloudApiController@getReplicaSplitShard');
    Route::get('getAvgShard/{nodeCount?}', 'Api\SolrCloudApiController@getAvgShard');
    
    Route::get('createNodeInfoTxt', 'Api\SolrCloudApiController@createNodeInfoTxt');
    Route::get('createSystemInfo', 'Api\SolrCloudApiController@createSystemInfo');
    
    
    Route::get("getSolrDataYearRate", 'Api\TestController@getSolrDocsDate');
    Route::get("qtimeTest", 'Api\SolrCloudApiController@qtimeTest');
    
    Route::get("getShardCnt", 'Api\SolrCloudApiController@getShardCnt');
    
});

