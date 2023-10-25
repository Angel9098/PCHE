<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ImagenController;

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

Route::group(['middleware' => 'auth'], function () {


    // Ruta de registro
    Route::get('registroTemp', 'AuthController@showRegistrationForm')->name('register');
    Route::post('registrarse', 'AuthController@register')->name('registrarse');

    //Rutas para empresa
    Route::post('empresa', 'EmpresaController@create')->name('createdata');
    Route::get('empresas', 'EmpresaController@allempresas')->name('empresas');
    Route::delete('empresas', 'EmpresaController@deleteEmpresa');
    Route::get('empresabyid', 'EmpresaController@empresaById');
    Route::post('empleados/filtro/busqueda', 'EmpleadoController@bEmpleadoF')->name('empleadosBusquedafiltro');
    Route::post('/actualizar/empresa', 'EmpresaController@update')->name('updateEmpresa');

    //Rutas para area
    Route::get('areas', 'AreaController@listaDetalleAreas')->name('areasdetalle');
    Route::get('areabyid', 'AreaController@areaById');
    //Route::get('horarios/area', 'AreaController@horariosArea')->name('areasbyempresa');
    Route::get('empresa/areas', 'EmpresaController@getAreasEmpresa');
    Route::post('areas/create', 'AreaController@createArea');
    Route::put('areas/update', 'AreaController@updateArea');
    Route::delete('areas/delete', 'AreaController@deleteArea');
    Route::get('areas/jefes', 'AreaController@listaJefeEmpleado');
    Route::get('areas/jefesAsignados', 'AreaController@listadoJefeAsignadoDisponible');



    //rutas para horarios
    Route::get('horarios/consulta', 'HorarioController@consultaDehorarioPorEmpleado')->name('horariobypersona');
    Route::get('horarios', 'HorarioController@index')->name('horarios');

    //Rutas para empleado
    Route::get('empleados', 'EmpleadoController@allempleados')->name('empleados');
    Route::post('empleados/save', 'EmpleadoController@store')->name('empleados_save');
    Route::delete('empleados/eliminar', 'EmpleadoController@eliminarEmpleados')->name('empleadosEliminar');
    Route::post('empleados/busqueda', 'EmpleadoController@empleadosBusquedaNombre')->name('empleadosBusqueda');
    Route::post('empleados/actualizar', 'EmpleadoController@actualizarEmpleados')->name('empleadosActualizar');
    Route::post('empleados/crear', 'EmpleadoController@crearEmpleado')->name('empleadoCrear');
    Route::get('empleados/busqueda/{nombre}', 'EmpleadoController@empleadosBusquedaNombre')->name('empleadosBusqueda');
    Route::get('empleado_dui', 'EmpleadoController@empleadoByDui')->name('bucarEmpleado');
    Route::post('actualizarcontra', 'EmpleadoController@actualizarContrasenia')->name('actualizarpass');
    Route::get('empleados/busqueda/empresa', 'EmpleadoController@busquedaEmpleadoPorEmpresa')->name('empleadosBusquedaEmpresa');
    Route::get('empleadobyid', 'EmpleadoController@empleadoById');
    Route::get('empleados_area', 'EmpleadoController@allEmpleadosAreaEmpresa')->name('empleadosbyAreaEmpresa');
    Route::get('empleados/horas_extra', 'EmpleadoController@empleadosConHorasExtra')->name('empleadosbyHorasExtra');
    Route::get('findempleadobyid', 'EmpleadoController@findEmpleadoById');
    Route::get('empleados/existeEmail', 'EmpleadoController@validarEmail');
    Route::get('empleados/existeDui', 'EmpleadoController@validarDui');
    Route::post('planilla/quincenal', 'EmpleadoController@planillaQuincenal');


    //rutas para los calculos
    Route::post('/calculo_horas', 'CalculosHorasController@createCalculo');
    Route::post('/calculos', 'CalculosHorasController@allCalculos');
    Route::get('/calculo_horas/graficasPorEmpresa', 'CalculosHorasController@graficaCalculoDeHorasPorMesEmpresa');
    Route::get('/calculo_horas/graficaDeTresMeses', 'CalculosHorasController@graficaCalculoDeHorasDeTresMeses');
    Route::get('/datos_export', 'CalculosHorasController@datosExport');
    Route::get('/datos/procesados', 'HoraExtraCotroller@horasProcesadaNo');



    Route::post('horas_extra/crear', 'HoraExtraCotroller@createHoraExtra')->name('horas_extra');
    Route::post('horas_extra', 'HoraExtraCotroller@allHoras')->name('horas_extra.all');
    Route::get('limpiar_horas', 'HorarioController@limpiarTabla');

    //ruta subida imagenes
    Route::post('/editarusuario', 'AuthController@editarPerfilUser');

    //Ruta Cortes
    Route::get('cortes', 'CorteController@index')->name('cortes');
    Route::post('cortes/crear', 'CorteController@create')->name('crearCorte');
    Route::get('corte/vigente', 'CorteController@getCorteVigente')->name('corteVigente');

    //Ruta Dashboard
    Route::get('dashboard/horasExtraEmpresa', 'DashboardController@obtenerHorasExtraPorEmpresa');
    Route::get('dashboard/horasExtraTotal', 'DashboardController@obtenerTotalSalarioHorasExtra');
    Route::get('dashboard/fechaReciente', 'DashboardController@obtenerFechaReciente');
    Route::get('dashboard/recuentoHoras','HoraExtraCotroller@horasProcesadaNo');
});


Route::get('imagenes/{nombre}', [ImagenController::class, 'mostrar'])->name('imagen.mostrar');
