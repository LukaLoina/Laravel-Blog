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

/* 
   Rute imaju sljedeći oblik.
   Route::<HTTP poziv>(<oblik URL-a>, <metoda koja se izvršava>)
   HTTP pozivi koje web pretraživači poznaju su GET i POST.
   GET u pravilu služi za dohvačanje web stranice.
   POST u pravilu služi kako bi se od web servera zatražilo da napravi neku promjenu.
   Oblik URL-a određuje kako će izgledati poveznice na stranici.
   Parametri rute su označeni sa {}. Pomoću parametara se vadi dio iz rute te se prosljeđuje metodi koja se poziva. 
   Metoda definirana u ruti je funkcija koja se izvodi kada korisnik pristupa nekoj ruti.

   ->name() je poziv metode name kojom se ruti daje ime kako bi se lakše pozvala u kodu.
*/

Route::get('/', 'HomeController@sort')->name('welcome'); /* Ruta za početnu stranicu */
Route::get('/sort/{order_by}', 'HomeController@sort')->name('sort'); /* Ruta za prikaz stranice sa filtriranim i sortiranim blog postovima. */
Route::post('/sort/{order_by}', 'HomeController@filter')->name('filter'); /* Ruta koja se poziva kada korisnik šalje podatke forme za filtriranje i sortiranje blog postova. */
Auth::routes(); /* Dodavanje ruta potrebnih za registraciju, prijavu, odjavu, itd. */

Route::get('/home', 'HomeController@index')->name('home'); /* Ruta koja prikazuje korisniku sa detaljima i njegovim blog postovima. */


Route::get('/create', 'PostsController@createForm')->name('createForm'); /* Ruta za prikaz stranice sa formom za stvaranje novog blog posta.*/
Route::post('/create', 'PostsController@create')->name('create'); /* Ruta koja se poziva kada korisnik preko forme stvara novi blog post. */
Route::get('/read/{id}', 'PostsController@read')->name('read'); /* Ruta za prikaz stranice sa sadržajem i komentarima blog posta. */
Route::get('update/{id}', 'PostsController@updateForm')->name('updateForm'); /* Ruta za prikaz stranice sa formom za izmjenu postoječeg blog posta. */
Route::post('update/{id}', 'PostsController@update')->name('update'); /* Ruta koja se poziva kada korisnik preko forme pošalje izmjene. */
Route::get('delete/{id}', 'PostsController@deleteForm')->name('deleteForm'); /* Ruta za prikaz stranice koja pita korisnika je li siguran da želi obrisati blog post. */
Route::post('delete/{id}', 'PostsController@delete')->name('delete'); /* Ruta koja se poziva kada korisnik u formi potvrdi da želi obrisati blog post. */

Route::post('comment/{id}', 'CommentsController@comment')->name('comment'); /* Ruta koja se poziva kada korisnik objavljuje novi komentar.*/

Route::post('like/{id}', 'LikesController@like')->name('like'); /* Ruta za postavljanje likea na blog post. */
Route::post('unlike/{id}', 'LikesController@unlike')->name('unlike'); /* Ruta za micanje likea sa blog posta. */
