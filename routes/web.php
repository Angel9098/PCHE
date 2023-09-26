<?php

use Illuminate\Support\Facades\Route;

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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'AuthController@showLoginForm')->name('login');
Route::post('login', 'AuthController@login')->name('ingresar');
Route::get('logout', 'AuthController@logout')->name('salir');

// Ruta de registro
Route::get('registroTemp', 'AuthController@showRegistrationForm')->name('register');
Route::post('registrarse', 'AuthController@register')->name('registrarse');

//Rutas para empresa
Route::post('empresa', 'EmpresaController@create')->name('createdata');
Route::get('empresas', 'EmpresaController@allempresas')->name('empresas');
Route::delete('empresas', 'EmpresaController@deleteEmpresa');
Route::get('empresabyid', 'EmpresaController@empresaById');
Route::post('empleados/filtro/busqueda', 'EmpleadoController@bEmpleadoF')->name('empleadosBusquedafiltro');

//Rutas para area
Route::get('areas', 'AreaController@index')->name('areas');
Route::get('areabyid', 'AreaController@areaById');
Route::get('horarios/area', 'AreaController@horariosArea')->name('areasbyempresa');
Route::get('empresa/areas', 'EmpresaController@getAreasEmpresa');
Route::post('empresa/update', 'EmpresaController@update')->name('updateEmpresa');
//rutas para horarios
Route::get('horarios/consulta', 'HorarioController@consultaDehorarioPorEmpleado')->name('horariobypersona');
Route::get('horarios', 'HorarioController@index')->name('horarios');

//Rutas para empleado
Route::get('empleados', 'EmpleadoController@allempleados')->name('empleados');
Route::post('empleados/save', 'EmpleadoController@store')->name('empleados_save');
Route::delete('empleados/eliminar', 'EmpleadoController@eliminarEmpleados')->name('empleadosEliminar');
Route::post('empleados/busqueda', 'EmpleadoController@empleadosBusquedaNombre')->name('empleadosBusqueda');
Route::put('empleados/actualizar', 'EmpleadoController@actualizarEmpleados')->name('empleadosActualizar');
Route::post('empleados/crear', 'EmpleadoController@crearEmpleado')->name('empleadoCrear');
Route::get('empleados/busqueda/{nombre}', 'EmpleadoController@empleadosBusquedaNombre')->name('empleadosBusqueda');
Route::get('empleado_dui', 'EmpleadoController@empleadoByDui')->name('bucarEmpleado');
Route::post('actualizarcontra', 'EmpleadoController@actualizarContrasenia')->name('actualizarpass');
Route::get('empleados/busqueda/empresa', 'EmpleadoController@busquedaEmpleadoPorEmpresa')->name('empleadosBusquedaEmpresa');
Route::get('empleadobyid', 'EmpleadoController@empleadoById');
Route::get('empleados_area', 'EmpleadoController@allEmpleadosAreaEmpresa')->name('empleadosbyAreaEmpresa');
Route::get('empleados/horas_extra', 'EmpleadoController@empleadosConHorasExtra')->name('empleadosbyHorasExtra');
Route::get('findempleadobyid', 'EmpleadoController@findEmpleadoById');

//rutas para los calculos
Route::post('/calculo_horas', 'CalculosHorasController@createCalculo');
Route::post('/calculos', 'CalculosHorasController@allCalculos');


Route::post('horas_extra/crear', 'HoraExtraCotroller@createHoraExtra')->name('horas_extra');
Route::post('horas_extra', 'HoraExtraCotroller@allHoras')->name('horas_extra.all');
Route::get('limpiar_horas', 'HorarioController@limpiarTabla');

//ruta subida imagenes
Route::post('/editarusuario', 'AuthController@editarPerfilUser');

//Ruta Cortes
Route::get('cortes', 'CorteController@index')->name('cortes');
Route::post('cortes/crear', 'CorteController@create')->name('crearCorte');

Route::group(['middleware' => 'auth'], function () {
    Route::get('dashboard', 'DashboardController@index')->name('dashboard');
});
