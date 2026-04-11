<?php


Route::get('/', function () {
    return redirect()->route('home');
});


Auth::routes();

Route::group(['middleware' => 'auth'], function () {
    
    // 文字列だけ
    Route::get('/home', 'EventController@index')->name('home');

    // リソースコントローラー
    Route::resource('events', 'EventController');

    Route::get('/events/{event}/apply', 'EventController@apply')->name('events.apply');
    Route::post('/events/{event}/apply', 'EventController@storeApplication')->name('events.apply.store');
    Route::get('/events/{event}/report', 'EventController@report')->name('events.report');
    Route::post('/events/{event}/report', 'EventController@storeReport')->name('events.report.store');
    Route::post('/events/{event}/bookmark', 'EventController@bookmark')->name('events.bookmark');

    Route::get('/mypage', 'EventController@mypage')->name('mypage');
    Route::get('/profile', 'EventController@profile')->name('profile');
    Route::get('/profile/edit', 'EventController@editProfile')->name('profile.edit');
    Route::post('/profile/update', 'EventController@updateProfile')->name('profile.update');

    Route::get('/profile/delete', 'EventController@confirmDelete')->name('profile.delete.confirm');
    Route::delete('/profile/delete', 'EventController@deleteAccount')->name('profile.delete');

    Route::get('/reports', 'ReportController@index')->name('reports.index');
});