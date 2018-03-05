<?php
$DI = \DI::instance();
$AdminUser = $DI->get(AdminUser::class);

Route::bind_action('/p1',                  'Blog\User@run');
Route::bind_action('/p2',           'Application@show_registration');
Route::bind_action('/p2/completed', 'Application@show_thankyou');
Route::bind_action('/p2/sorry',     'Application@show_sorry');
Route::bind_action('/p2/error',     'Application@show_error');
Route::bind_action('/s',           'Subscription@show_subscribe');
Route::bind_action('/o',             'Offer_link@run');

Route::bind_action('/sm.xml',            'Sitemap@save');
Route::bind_action('/bl.xml',               'Blog\User@save_rss');