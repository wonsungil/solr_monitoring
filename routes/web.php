<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('index');
});

Route::group(['prefix'=>'sc'], function() {
    
    Route::get('shards', 'SolrCloudController@shards');
    Route::get('nodeInfo', 'SolrCloudController@nodeInfo');
    Route::get('operations', 'SolrCloudController@operations');
    Route::get('runningMap', 'SolrCloudController@runningMap');
    Route::get('state', 'SolrCloudController@state');
    Route::get('shardGraph', 'SolrCloudController@shardGraph');
    Route::get('diskUsageGroupBy', 'SolrCloudController@diskUsageGroupBy');
    Route::get('nodes', 'SolrCloudController@nodes');
    
    Route::get('shardCnt', 'SolrCloudController@shardReplicaCnt');
    
});

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/check', 'SolrCloudController@check');
Route::get('/single', 'SolrCloudController@single');