<?php

class indexController extends Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function index()
	{
		echo "ORACLE ORDEN DE TRABAJO";
		/*$this->_view->setCss_Gral([
			'bootstrap-4.6.0-dist/css/bootstrap.min',
			'font-awesome/css/font-awesome.min',
		]);

		$this->_view->setJs_Gral([
			'js/jquery-3.5.1.min',
			'js/sweetalert.min',
			'bootstrap-4.6.0-dist/js/bootstrap.min',
		]);

		$this->_view->setJs(['index']);
		$this->_view->renderizar("index");*/
	}

	public function getTraslados()
	{
		$numOP = trim($_POST['numOP']);

		$objModel = $this->loadModel('index');
		$res = $objModel->getTraslados($numOP);

		header('Content-type: application/json; charset=utf-8');
		echo json_encode(["res" => $res]);
	}

	//public function imprimir($nroTraslado,$dato_almacen)
	public function imprimir()
	{
		//header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		$objModel = $this->loadModel("index");
		$cabecera_TI = $objModel->getHead(intval($input["dato"]['id']));
		
		$mpdf->SetDefaultFont("Arial");
		
		/*$html="<style>@page {
				 margin-top: 20px;
				 margin-bottom: 20px;
				 margin-right: 20px;
				 margin-left: 20px;
				}</style>";
				
		$mpdf->WriteHTML($html);*/
		
		$mpdf = new mPDF('utf-8', 'A4', '', '', 5, 5, 5, 5, 4, 4);
		
		$mpdf -> SetTitle('ORDEN DE TRASLADO');

		$mpdf->SetDefaultFont("Arial");

		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
						<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>F-AL.012.02</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRASLADO</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><strong>N°:</strong> ".$cabecera_TI[0]['NroTraslado']."</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><strong>Tipo de operación:</strong> ".$cabecera_TI[0]['TipoOperacion']."</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->WriteHTML("
			<table class='tabla' width='100%' style='border:#000000 1px solid;'>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['codarticulo']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['nomarticulo']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['nomoperaciones']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['canprod']." ".$cabecera_TI[0]['Unidad']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['FechaCreacion']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['Lote']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
					<td class='celda' style='font-size:11px;'>".substr($cabecera_TI[0]['fecfabricacion'],3,2)."-".substr($cabecera_TI[0]['fecfabricacion'],6,4)."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
					<td class='celda' style='font-size:11px;'>".substr($cabecera_TI[0]['feccaducidad'],3,2)."-".substr($cabecera_TI[0]['feccaducidad'],6,4)."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
					<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['linea']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
					<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);

		$mpdf->WriteHTML("
			<table class='tabla1' width='100%'>
				<tr class='fila1'>
					<td class='celda1' style='width:8%;font-size:10px;text-align:center;'><strong>Código</strong></td>
					<td class='celda1' style='width:23%;font-size:10px;'><strong>Descripcion</strong></td>
					<td class='celda1' style='width:6%;font-size:10px;'><strong>Deposito</strong></td>
					<td class='celda1' style='width:7%;font-size:10px;'><strong>Cantidad Generada</strong></td>
					<td class='celda1' style='width:7%;font-size:10px;text-align:center;'><strong>Cantidad Dispensada</strong></td>
					<td class='celda1' style='width:3%;font-size:10px;'><strong>Und</strong></td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>Lote</strong></td>
					<td class='celda1' style='width:9%;font-size:10px;text-align:center;'><strong>Número Análisis</strong></td>
					<td class='celda1' style='width:4%;font-size:10px;'><strong>Pot%/ P.E./Ver</strong></td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>Codigo balanza</strong></td>
					<td class='celda1' style='width:4%;font-size:10px;'><strong>V°B° Alm.</strong></td>
					<td class='celda1' style='width:4%;font-size:10px;'><strong>V°B° Pro.</strong></td>
				</tr>
		");
		
		if(intval($input["dato"]['almacen'])==1)
		{
			$dato_articulos = $objModel->getArticulos($cabecera_TI[0]['idtransaccion'],$cabecera_TI[0]['idOP']);
			
			$datos_mysql = $objModel->queryMysql($cabecera_TI[0]['idOP']);

			if(count($datos_mysql)>0){
				$res_dato_detalle_OP = $this->custom_array_merge($dato_articulos, $datos_mysql);
			}else{
				$res_dato_detalle_OP = $dato_articulos;
			}

			foreach($res_dato_detalle_OP as $art){

				if($art['principActivo']=='T'){
					$principio_activo="background-color:#D6DBDF;color:#000000";
				}else{
					$principio_activo="";
				}
				
				//if($dato[0]['idOP']=='85530'){
				//	$cant_generada = $art['canttrasladar'];
				//}else{
				//	$cant_generada = $art['cantidad_mysql'];
				//}
				
				$new_dato = explode("#",$art['numserielote']);
				
				$pot_peso_ver = $new_dato[1]."/".$new_dato[3];
				if($new_dato[3]==""){
					$pot_peso_ver = $new_dato[1];
				}
				
				//cantidad_mysql
				$mpdf->WriteHTML("
					<tr class='fila1'>
						<td class='celda1' style='width:8%;font-size:10px;".$principio_activo."'>".$art['codarticulo']."</td>
						<td class='celda1' style='width:23%;font-size:10px;".$principio_activo."'>".$art['nomarticulo2']."</td>
						<td class='celda1' style='width:6%;font-size:10px;text-align:center;".$principio_activo."'>".$art['desdedeposito']."</td>
						<td class='celda1' style='width:7%;font-size:10px;text-align:right;".$principio_activo."'>".number_format($art['cantidad_mysql'], 4, '.', ',')."</td>
						<td class='celda1' style='width:7%;font-size:10px;text-align:right;".$principio_activo."'>".number_format($art['cantidad'], 4, '.', ',')."</td>
						<td class='celda1' style='width:3%;font-size:10px;text-align:center;".$principio_activo."'>".$art['unidad']."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$new_dato[0]."</td>
						<td class='celda1' style='width:9%;font-size:10px;text-align:center;".$principio_activo."'>".$new_dato[2]."</td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;".$principio_activo."'>".$pot_peso_ver."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'></td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;".$principio_activo."'></td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;".$principio_activo."'></td>
					</tr>
				");
			}

			$mpdf->WriteHTML("
				</table>
			");
			
			$mpdf->Ln(3);
			
			date_default_timezone_set('America/Lima');
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='40%'>
					<tr>
						<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Descarga / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado / Fecha</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".ucwords($cabecera_TI[0]['NomDescarga'])."<br>".$cabecera_TI[0]['fecfirmaDescarga']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".ucwords($cabecera_TI[0]['NomVerifica'])."<br>".$cabecera_TI[0]['fecfirmaVerifica']."</td>
					</tr>
					<tr>
						<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento de la Calidad</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Verificación de Picking y dispensación</strong></td>
						<td colspan=2 style='font-size:11px;'><strong>Recepción OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Picking realizado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Dispensado / Fecha</td>
						<td style='width:2%;border-top:#FFFFFF;'></td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Entregado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Revisado / Fecha</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td style='width:2%;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Supervisor Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td style='width:2%;'></td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
						<td colspan=4 style='font-size:11px;'><strong>Tipo de Traslado</strong></td>
					</tr>
					<tr>
						<td colspan=4 style='font-size:10px;'>".$cabecera_TI[0]['Nota']."</td>
						<td colspan=4 style='font-size:10px;'>".$cabecera_TI[0]['tipoTraslado']."</td>
					</tr>
				</table>
			");

		}else{
			$dato_articulos = $objModel->getArticulos_ID($cabecera_TI[0]['idtransaccion']);
			
			foreach($dato_articulos as $art){

				$new_dato = explode("#",$art['numserielote']);
				
				$mpdf->WriteHTML("
					<tr class='fila1'>
						<td class='celda1' style='width:8%;font-size:10px;'>".$art['codarticulo']."</td>
						<td class='celda1' style='width:23%;font-size:10px;'>".$art['nomarticulo']."</td>
						<td class='celda1' style='width:6%;font-size:10px;text-align:center;'>".$art['desdedeposito']."</td>
						<td class='celda1' style='width:7%;font-size:10px;text-align:right;'>".number_format($art['canttrasladar'], 4, '.', ',')."</td>
						<td class='celda1' style='width:7%;font-size:10px;text-align:right;'>".number_format($art['cantidad'], 4, '.', ',')."</td>
						<td class='celda1' style='width:3%;font-size:10px;text-align:center;'>".$art['unidad']."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;'>".$new_dato[0]."</td>
						<td class='celda1' style='width:9%;font-size:10px;text-align:center;'>".$new_dato[2]."</td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;'>".$new_dato[1]."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;'></td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;'></td>
						<td class='celda1' style='width:4%;font-size:10px;text-align:center;'></td>
					</tr>
				");
			}

			$mpdf->WriteHTML("
				</table>
			");
			
			$mpdf->Ln(3);
			
			//date_default_timezone_set('America/Lima');
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='40%'>
					<tr>
						<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Descarga / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado / Fecha</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".ucwords($cabecera_TI[0]['NomDescarga'])."<br>".$cabecera_TI[0]['fecfirmaDescarga']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".ucwords($cabecera_TI[0]['NomVerifica'])."<br>".$cabecera_TI[0]['fecfirmaVerifica']."</td>
					</tr>
					<tr>
						<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento de la Calidad</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Verificación de Picking y dispensación</strong></td>
						<td colspan=2 style='font-size:11px;'><strong>Recepción OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Picking realizado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Dispensado / Fecha</td>
						<td style='width:2%;border-top:#FFFFFF;'></td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Entregado / Fecha</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Revisado / Fecha</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td style='width:2%;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
						<td class='celda1' style='font-size:9px;text-align:center; height:60px;'></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Supervisor Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td style='width:2%;'></td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
					</tr>
					<tr>
						<td colspan=4 style='font-size:10px;'>".$cabecera_TI[0]['Nota']."</td>
					</tr>
				</table>
			");
			
		}
		
		if(intval($input["dato"]['almacen'])==2)
		{
			
			// Inicio de PDF Orden de Trabajo
		
			$mpdf->AddPage(); 
			
			$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo_ID($cabecera_TI[0]['idOP']);

			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td rowspan='4'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
							<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
						</td>
						<td style='text-align:right;'>
							<p style='font-size:16px;font-weight:bold;'>F-LOG.004.05</p>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>
							<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRABAJO</p>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>
							<p style='font-size:14px;'><strong>Tipo de Orden de Trabajo:</strong> ".$dato_cabecera_OP[0]['TipOT']."</p>
						</td>
					</tr>
					
				</table>
			");
			
			$mpdf->Ln(3);

			$mpdf->WriteHTML("
				<table class='tabla' width='100%' style='border:#000000 1px solid;'>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['codarticulo']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['nomarticulo']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
						<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['NroOpe']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['canprod']." ".$cabecera_TI[0]['Unidad']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
						<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['FechaCreacion']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['Lote']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
						<td class='celda' style='font-size:11px;'>".substr($cabecera_TI[0]['FecFab'],3,2)."-".substr($cabecera_TI[0]['FecFab'],6,4)."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
						<td class='celda' style='font-size:11px;'>".substr($cabecera_TI[0]['feccaducidad'],3,2)."-".substr($cabecera_TI[0]['feccaducidad'],6,4)."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['linea']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
						<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3); 
			
			$mpdf->WriteHTML("
				<table class='tabla1' width='100%'>
					<tr class='fila1'>
						<td class='celda1' style='width:15%;font-size:10px;'><strong>Código</strong></td>
						<td class='celda1' style='width:60%;font-size:10px;'><strong>Descripcion</strong></td>
						<td class='celda1' style='width:15%;font-size:10px;text-align:center;'><strong>Cantidad</strong></td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>UND</strong></td>
					</tr>
			");
			
			$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo_ID($cabecera_TI[0]['idOP']);
			
			foreach($dato_detalle_OP as $art1){
				
				if($art1['principActivo']=='T'){
					//$principio_activo="background-color:#D6DBDF;color:#000000";
					$principio_activo="";
				}else{
					$principio_activo="";
				}

				$mpdf->WriteHTML("
					<tr class='fila1'>
						<td class='celda1' style='width:15%;font-size:10px;".$principio_activo."'>".$art1['codigo']."</td>
						<td class='celda1' style='width:60%;font-size:10px;".$principio_activo."'>".$art1['articulo']."</td>
						<td class='celda1' style='width:15%;font-size:10px;text-align:center;".$principio_activo."'>".number_format($art1['cantidad'], 3, '.', ',')."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$art1['und']."</td>
					</tr>
				");
			}
			
			$mpdf->WriteHTML("
				</table>
			");
			
			$mpdf->Ln(3);
			
			date_default_timezone_set('America/Lima');
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Verificado por</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AprobNomApe']."<br>".$dato_cabecera_OP[0]['firmaaaprobado']."</td>
						<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RecibNomApe']."<br>".$dato_cabecera_OP[0]['firmarecibido']."</td>
					</tr>
					<tr>
						<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					</tr>
					<tr>
						<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
						<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
					</tr>
					<tr>
						<td colspan=4 style='font-size:10px;'>".$dato_cabecera_OP[0]['Nota']."</td>
					</tr>
				</table>
			");
				
		}else{
			
			// Inicio de PDF Orden de Trabajo
		
			$mpdf->AddPage(); 
			
			$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo($cabecera_TI[0]['idOP']);

			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td rowspan='4'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
							<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
						</td>
						<td style='text-align:right;'>
							<p style='font-size:16px;font-weight:bold;'>F-LOG.004.05</p>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>
							<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRABAJO</p>
						</td>
					</tr>
					<tr>
						<td style='text-align:right;'>
							<p style='font-size:14px;'><strong>Tipo de Orden de Trabajo:</strong> ".$dato_cabecera_OP[0]['TipOT']."</p>
						</td>
					</tr>
					
				</table>
			");
			
			$mpdf->Ln(3);

			$mpdf->WriteHTML("
				<table class='tabla' width='100%' style='border:#000000 1px solid;'>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['codarticulo']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['nomarticulo']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
						<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['NroOpe']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['canprod']." ".$cabecera_TI[0]['Unidad']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
						<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['FechaCreacion']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['Lote']."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
						<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FecFab'],3,2)."-".substr($dato_cabecera_OP[0]['FecFab'],6,4)."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
						<td class='celda' style='font-size:11px;'>".substr($cabecera_TI[0]['feccaducidad'],3,2)."-".substr($cabecera_TI[0]['feccaducidad'],6,4)."</td>
					</tr>
					<tr class='fila'>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
						<td class='celda' style='font-size:11px;'>".$cabecera_TI[0]['linea']."</td>
						<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
						<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(3); 
			
			$mpdf->WriteHTML("
				<table class='tabla1' width='100%'>
					<tr class='fila1'>
						<td class='celda1' style='width:15%;font-size:10px;'><strong>Código</strong></td>
						<td class='celda1' style='width:60%;font-size:10px;'><strong>Descripcion</strong></td>
						<td class='celda1' style='width:15%;font-size:10px;text-align:center;'><strong>Cantidad</strong></td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>UND</strong></td>
					</tr>
			");
			
			$where1="";
			$where2="";
			if($dato_cabecera_OP[0]['TipOT']=="REACONDICIONADO"){
				$where1 .= " where itemsource like 'STOCK%'";
				$where2 .= "";
			}else{
				$where1 .= "";
				$where2 .= " and (I.fullname LIKE 'M%' or I.fullname LIKE 'B%')";
			}
			
			$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo(intval($cabecera_TI[0]['idOP']),$where1,$where2);
			
			foreach($dato_detalle_OP as $art1){
				
				if($art1['principActivo']=='T'){
					$principio_activo="background-color:#D6DBDF;color:#000000";
				}else{
					$principio_activo="";
				}

				$mpdf->WriteHTML("
					<tr class='fila1'>
						<td class='celda1' style='width:15%;font-size:10px;".$principio_activo."'>".$art1['codigo']."</td>
						<td class='celda1' style='width:60%;font-size:10px;".$principio_activo."'>".$art1['articulo']."</td>
						<td class='celda1' style='width:15%;font-size:10px;text-align:center;".$principio_activo."'>".number_format($art1['cantidad'], 3, '.', ',')."</td>
						<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$art1['und']."</td>
					</tr>
				");
			}
			
			$mpdf->WriteHTML("
				</table>
			");
			
			$mpdf->Ln(3);
			
			date_default_timezone_set('America/Lima');
			
			if(intval($dato_cabecera_OP[0]['NroOpe'])<585){
				$mpdf->WriteHTML("
					<table class='tabla2' width='100%'>
						<tr>
							<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Verificado por</td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AjuNomApe']."<br>".$dato_cabecera_OP[0]['firmaajustado']."</td>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['VerNomApe']."<br>".$dato_cabecera_OP[0]['firmaverificado']."</td>
						</tr>
						<tr>
							<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
							<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
							<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
							<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento</td>
						</tr>
					</table>
				");
			}else{
				$mpdf->WriteHTML("
					<table class='tabla2' width='80%'>
						<tr>
							<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
							<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AjuNomApe']."<br>".$dato_cabecera_OP[0]['firmaajustado']."</td>
						</tr>
						<tr>
							<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
							<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
							<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
						</tr>
						<tr>
							<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
							<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
						</tr>
					</table>
				");
			}
			
			
			
			$mpdf->Ln(3);
			
			$mpdf->WriteHTML("
				<table class='tabla2' width='100%'>
					<tr>
						<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
					</tr>
					<tr>
						<td colspan=4 style='font-size:10px;'>".$dato_cabecera_OP[0]['Nota']."</td>
					</tr>
				</table>
			");
			
		}
		
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/tinv_".$input["dato"]['id']."_".$fecha.".pdf";

		$mpdf->Output($archivo, 'F');  //D: descarga directa, I: visualizacion, F: descarga en ruta especifica
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		header('Access-Control-Allow-Origin: *');

		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
		
	}
	
	//$numOP
	/*public function sendEmail()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='ajuste'){
			//["cpomiano@biomont.com.pe","mramirez@biomont.com.pe","aquinones@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","cpomiano@biomont.com.pe","mramirez@biomont.com.pe","aquinones@biomont.com.pe"];
		}else if($input["dato"]['email']=='aseguramiento'){
			//["crujel@biomont.com.pe","kcastillo@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","crujel@biomont.com.pe","kcastillo@biomont.com.pe"];
		}else if($input["dato"]['email']=='todos'){
			//["mramirez@biomont.com.pe","vgalan@biomont.com.pe","csuncion@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","vgalan@biomont.com.pe","csuncion@biomont.com.pe"];
		}
		
	
		$this->getLibrary('PHPMailer/PHPMailer');
		$this->getLibrary('PHPMailer/SMTP');

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true; //Habilita uso de usuario y contraseña
		$mail->SMTPSecure = 'tls';
		$mail->Mailer = 'smtp';
		$mail->Host = MAIL_APP_HOST;
		$mail->Username = MAIL_APP_USER;
		$mail->Password = MAIL_APP_PASSWORD;
		$mail->Port = 587;
		$mail->setFrom(MAIL_APP_USER);
		
		for($i = 0; $i < count($emails); $i++) {
			$mail->AddAddress($emails[$i]); //Destionatarios
		}
		
		//$mail->addAddress('jpena@biomont.com.pe');	
		//$mail->addCC('juanpm32@gmail.com'); //Envio aparece como copiado
		//$mail->addBCC('juanma1710@hotmail.com'); //Enviar oculto (no aparece como copiado)
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = 'Notificación de Orden de Trabajo - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']."</span></td>
								</tr>
								<tr>
									<td><strong>Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['numOP']."</td>
								</tr>
								<tr>
									<td><strong>Tipo de Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['tipoOT']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idOP']."' target='_blank'>Ver registro</a></td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
							</div>";
							
		$mail->Body   = $mailContent;

		if (!$mail->send()) {
			//echo "no :".$mail->ErrorInfo; //Error al enviar el correo
			$est=1; //error al enviar email
		} else {
			//echo "ok"; //Se envió correctamente
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}*/
	
	public function sendEmail_IDE()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='logistica'){
			$emails = ["jpena@biomont.com.pe","cbonilla@biomont.com.pe","avilchez@biomont.com.pe","etacunan@biomont.com.pe"];
		}else if($input["dato"]['email']=='almacen'){
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","etacunan@biomont.com.pe","id@biomont.com.pe","mmancilla@biomont.com.pe"];
		}else if($input["dato"]['email']=='todos'){
			$emails = ["jpena@biomont.com.pe","etacunan@biomont.com.pe","id@biomont.com.pe","csuncion@biomont.com.pe"];
		}
		
		$this->getLibrary('PHPMailer/PHPMailer');
		$this->getLibrary('PHPMailer/SMTP');

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true; //Habilita uso de usuario y contraseña
		$mail->SMTPSecure = 'tls';
		$mail->Mailer = 'smtp';
		$mail->Host = MAIL_APP_HOST;
		$mail->Username = MAIL_APP_USER;
		$mail->Password = MAIL_APP_PASSWORD;
		$mail->Port = 587;
		$mail->setFrom(MAIL_APP_USER);
		
		for($i = 0; $i < count($emails); $i++) {
			$mail->AddAddress($emails[$i]); //Destionatarios
		}
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = 'Notificación de Orden de Trabajo IDE - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." la <strong>Orden de Trabajo Piloto</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']."</span></td>
								</tr>
								<tr>
									<td><strong>Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['numOP']."</td>
								</tr>
								<tr>
									<td><strong>Tipo de Orden de Trabajo</strong></td>
									<td>:</td>
									<td>".$input["dato"]['tipoOT']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idOP']."' target='_blank'>Ver registro</a></td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
							</div>";
							
		$mail->Body   = $mailContent;

		if (!$mail->send()) {
			//echo "no :".$mail->ErrorInfo; //Error al enviar el correo
			$est=1; //error al enviar email
		} else {
			//echo "ok"; //Se envió correctamente
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}
	
	public function sendEmailTI()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='verificacion'){
			//["crujel@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","csuncion@biomont.com.pe","vgalan@biomont.com.pe","schullo@biomont.com.pe"];
		}
		
	
		$this->getLibrary('PHPMailer/PHPMailer');
		$this->getLibrary('PHPMailer/SMTP');

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true; //Habilita uso de usuario y contraseña
		$mail->SMTPSecure = 'tls';
		$mail->Mailer = 'smtp';
		$mail->Host = MAIL_APP_HOST;
		$mail->Username = MAIL_APP_USER;
		$mail->Password = MAIL_APP_PASSWORD;
		$mail->Port = 587;
		$mail->setFrom(MAIL_APP_USER);
		
		for($i = 0; $i < count($emails); $i++) {
			$mail->AddAddress($emails[$i]); //Destionatarios
		}
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = 'Notificación de Traslado de Inventario - Sistema NetSuite';

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." el <strong>Traslado de Inventario</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']."</span></td>
								</tr>
								<tr>
									<td><strong>Orden de Traslado</strong></td>
									<td>:</td>
									<td>".$input["dato"]['numTI']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idTI']."' target='_blank'>Ver registro</a></td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
							</div>";
							
		$mail->Body   = $mailContent;

		if (!$mail->send()) {
			//echo "no :".$mail->ErrorInfo; //Error al enviar el correo
			$est=1; //error al enviar email
		} else {
			//echo "ok"; //Se envió correctamente
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}
	
	/*public function sendEmailTI_cesar()
	{
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);

		if($input["dato"]['email']=='verificacion'){
			//$emails = ["jpena@biomont.com.pe","mramirez@biomont.com.pe","csuncion@biomont.com.pe","vgalan@biomont.com.pe","schullo@biomont.com.pe"];
			$emails = ["jpena@biomont.com.pe","fcastro@biomont.com.pe"];
		}
		
		$this->getLibrary('PHPMailer/PHPMailer');
		$this->getLibrary('PHPMailer/SMTP');

		$mail = new PHPMailer();

		$mail->isSMTP();
		$mail->SMTPDebug = false;
		$mail->SMTPAuth = true; //Habilita uso de usuario y contraseña
		$mail->SMTPSecure = 'tls';
		$mail->Mailer = 'smtp';
		$mail->Host = MAIL_APP_HOST;
		$mail->Username = MAIL_APP_USER;
		$mail->Password = MAIL_APP_PASSWORD;
		$mail->Port = 587;
		$mail->setFrom(MAIL_APP_USER);
		
		for($i = 0; $i < count($emails); $i++) {
			$mail->AddAddress($emails[$i]); //Destionatarios
		}
		
		$mail->isHTML(true); //Acepta HTML
		$mail->CharSet = "utf-8"; //Acepta caracteres
		$mail->Subject = "El usuario ".$input["dato"]['firmante']." ha ".$input["dato"]['accion']." el Traslado de Inventario ".$input["dato"]['numTI'];

		$mailContent	  = "<div class='row'>
							<h1 style='color:#45556E;'>Notificación de NetSuite</h1>
							<table>
								<tr>
									<td>Buen día:</td>
								</tr>
								<tr>
									<td>El usuario <strong>".$input["dato"]['firmante']."</strong> ha ".$input["dato"]['accion']." el <strong>Traslado de Inventario</strong> en el Sistema NetSuite.</td>
								</tr>
							</table>
							<br><br>
							<table>
								<tr>
									<td><strong>Concepto</strong></td>
									<td>:</td>
									<td><span>".$input["dato"]['concepto']."</span></td>
								</tr>
								<tr>
									<td><strong>Orden de Traslado</strong></td>
									<td>:</td>
									<td>".$input["dato"]['numTI']."</td>
								</tr>
								<tr>
									<td><strong>Almacén Origen</strong></td>
									<td>:</td>
									<td>".$input["dato"]['almacen_ori']."</td>
								</tr>
								<tr>
									<td><strong>Almacén Destino</strong></td>
									<td>:</td>
									<td>".$input["dato"]['almacen_desti']."</td>
								</tr>
								<tr>
									<td><strong>Link de Registro</strong></td>
									<td>:</td>
									<td><a href='https://6462530.app.netsuite.com/app/accounting/transactions/transaction.nl?id=".$input["dato"]['idTI']."' target='_blank'>Ver registro</a></td>
								</tr>
								<tr>
									<td><strong>Códigos de Artículos</strong></td>
									<td></td>
									<td></td>
								</tr>
							</table>
							<table style='border:1px solid black;border-collapse:collapse;'>
									<tr>
										<th style='border:1px solid black;padding:3px;'>Código</th>
										<th style='border:1px solid black;padding:3px;'>Descripción</th>
									</tr>";	
							foreach($input["dato"]['articulos'] as $art){
		$mailContent		.=		"<tr>
										<td style='border:1px solid black;padding:3px;'>".$art[0]."</td>
										<td style='border:1px solid black;padding:3px;'>".$art[1]."</td>
									</tr>";					
							}
		$mailContent		.=	"</table>	
							<br><br>
							<table>
								<tr>
									<td><h1 style='color:#45556E;'>Atentamente</h1></td>
								</tr>
								<tr>
									<td><h1 style='color:#45556E;'>Notificaciones NetSuite</h1></td>
								</tr>
							</table>
							</div>";
							
		$mail->Body   = $mailContent;

		if (!$mail->send()) {
			//echo "no :".$mail->ErrorInfo; //Error al enviar el correo
			$est=1; //error al enviar email
		} else {
			//echo "ok"; //Se envió correctamente
			$est=0; //email enviado
		}
						
		
		header("Content-type: application/json; charset=utf-8");
		echo json_encode(["est"=>$est]);

	}*/
	

	/*public function imprimirBOM()
	{
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("index");
		$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo(intval($input['dato']['idOT']));
		

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		
		$mpdf -> SetTitle('ORDEN DE TRABAJO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$html="<style>@page {
				 margin-top: 20px;
				 margin-bottom: 20px;
				 margin-right: 20px;
				 margin-left: 20px;
				}</style>";
				
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
						<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>F-LOG.004.05</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRABAJO</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><strong>Tipo de Orden de Trabajo:</strong> ".$dato_cabecera_OP[0]['TipOT']."</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);

		$mpdf->WriteHTML("
			<table class='tabla' width='100%' style='border:#000000 1px solid;'>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['codProd']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['producto1']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['NroOpe']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['CantProd']." ".$dato_cabecera_OP[0]['unidad']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['FechaCreacion']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Lote']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FecFab'],3,2)."-".substr($dato_cabecera_OP[0]['FecFab'],6,4)."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FexExp'],3,2)."-".substr($dato_cabecera_OP[0]['FexExp'],6,4)."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Linea']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
					<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3); 
		
		$mpdf->WriteHTML("
			<table class='tabla1' width='100%'>
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;'><strong>Código</strong></td>
					<td class='celda1' style='width:60%;font-size:10px;'><strong>Descripcion</strong></td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;'><strong>Cantidad</strong></td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>UND</strong></td>
				</tr>
		");
		
		$where1="";
		$where2="";
		if($dato_cabecera_OP[0]['TipOT']=="REACONDICIONADO"){
			$where1 .= " where itemsource like 'STOCK%'";
			$where2 .= "";
		}else{
			$where1 .= "";
			$where2 .= " and (I.fullname LIKE 'M%' or I.fullname LIKE 'B%')";
		}

		$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo(intval($input['dato']['idOT']),$where1,$where2);
		
		
		foreach($dato_detalle_OP as $art1){
			
			if(substr($dato_cabecera_OP[0]['codProd'],0,2)=="BK"){
				if(substr($art1['codigo'],0,2)=="BK"){
					continue;
				}
			}
			
			if($art1['principActivo']=='T'){
				$principio_activo="background-color:#D6DBDF;color:#000000";
			}else{
				$principio_activo="";
			}

			$mpdf->WriteHTML("
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;".$principio_activo."'>".$art1['codigo']."</td>
					<td class='celda1' style='width:60%;font-size:10px;".$principio_activo."'>".$art1['articulo']."</td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;".$principio_activo."'>".number_format($art1['cantidad'], 3, '.', ',')."</td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$art1['und']."</td>
				</tr>
			");
		}
		
		$mpdf->WriteHTML("
			</table>
		");
		
		$mpdf->Ln(3);
		
		date_default_timezone_set('America/Lima');
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Ajustado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Verificado por</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AjuNomApe']."<br>".$dato_cabecera_OP[0]['firmaajustado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['VerNomApe']."<br>".$dato_cabecera_OP[0]['firmaverificado']."</td>
				</tr>
				<tr>
					<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Producción</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Aseguramiento</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
				</tr>
				<tr>
					<td colspan=4 style='font-size:10px;'>".$dato_cabecera_OP[0]['Nota']."</td>
				</tr>
			</table>
		");
		
		
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OT_".$dato_cabecera_OP[0]['idOP']."_".$fecha.".pdf";

		$mpdf->Output($archivo, 'F');  //D: descarga directa, I: visualizacion, F: descarga en ruta especifica
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
	}*/

	public function imprimirBOM_IDE()
	{
		
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("index");
		$dato_cabecera_OP = $objModel->getCabeceraOrdenTrabajo_ID(intval($input['dato']['idOT']));
		

		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF();
		
		
		$mpdf -> SetTitle('ORDEN DE TRABAJO');
		
		$mpdf->SetDefaultFont("Arial");
		
		$html="<style>@page {
				 margin-top: 20px;
				 margin-bottom: 20px;
				 margin-right: 20px;
				 margin-left: 20px;
				}</style>";
				
		$mpdf->WriteHTML($html);
		
		$mpdf->WriteHTML("
			<table width='100%'>
				<tr>
					<td rowspan='4'>
						<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='140' height='55'>
						<p style='font-size:12px;'>Laboratorios Biomont S.A.</p>
					</td>
					<td style='text-align:right;'>
						<p style='font-size:16px;font-weight:bold;'>F-LOG.004.05</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:17px;font-weight:bold;'>ORDEN DE TRABAJO</p>
					</td>
				</tr>
				<tr>
					<td style='text-align:right;'>
						<p style='font-size:14px;'><strong>Tipo de Orden de Trabajo:</strong> ".$dato_cabecera_OP[0]['TipOT']."</p>
					</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);

		$mpdf->WriteHTML("
			<table class='tabla' width='100%' style='border:#000000 1px solid;'>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Código Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['codProd']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Producto:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['producto1']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>OT:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['NroOpe']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Cantidad a Producir:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['CantProd']." ".$dato_cabecera_OP[0]['unidad']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. registro:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['FechaCreacion']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Lote:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Lote']."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Fabricación:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FecFab'],3,2)."-".substr($dato_cabecera_OP[0]['FecFab'],6,4)."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>F. Expira:</td>
					<td class='celda' style='font-size:11px;'>".substr($dato_cabecera_OP[0]['FexExp'],3,2)."-".substr($dato_cabecera_OP[0]['FexExp'],6,4)."</td>
				</tr>
				<tr class='fila'>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Línea:</td>
					<td class='celda' style='font-size:11px;'>".$dato_cabecera_OP[0]['Linea']."</td>
					<td class='celda' style='text-align:right;font-size:11px;font-weight:bold;'>Principio activo:</td>
					<td class='celda' style='font-size:11px;'><p style='background-color:#D6DBDF;color:#D6DBDF;'>Hola</p></td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3); 
		
		$mpdf->WriteHTML("
			<table class='tabla1' width='100%'>
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;'><strong>Código</strong></td>
					<td class='celda1' style='width:60%;font-size:10px;'><strong>Descripcion</strong></td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;'><strong>Cantidad</strong></td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;'><strong>UND</strong></td>
				</tr>
		");

		$dato_detalle_OP = $objModel->getDetalleOrdenTrabajo_ID(intval($input['dato']['idOT']));
		
		foreach($dato_detalle_OP as $art1){
			
			if($art1['principActivo']=='T'){
				//$principio_activo="background-color:#D6DBDF;color:#000000";
				$principio_activo="";
			}else{
				$principio_activo="";
			}

			$mpdf->WriteHTML("
				<tr class='fila1'>
					<td class='celda1' style='width:15%;font-size:10px;".$principio_activo."'>".$art1['codigo']."</td>
					<td class='celda1' style='width:60%;font-size:10px;".$principio_activo."'>".$art1['articulo']."</td>
					<td class='celda1' style='width:15%;font-size:10px;text-align:center;".$principio_activo."'>".number_format($art1['cantidad'], 3, '.', ',')."</td>
					<td class='celda1' style='width:10%;font-size:10px;text-align:center;".$principio_activo."'>".$art1['und']."</td>
				</tr>
			");
		}
		
		$mpdf->WriteHTML("
			</table>
		");
		
		$mpdf->Ln(3);
		
		date_default_timezone_set('America/Lima');
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=2 style='font-size:11px;'><strong>Revisión y descarga de OT</strong></td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Emitido por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Revisado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Aprobado por</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Recibido por</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['EmiNomApe']."<br>".$dato_cabecera_OP[0]['firmaemitido']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RevNomApe']."<br>".$dato_cabecera_OP[0]['firmarevisado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['AprobNomApe']."<br>".$dato_cabecera_OP[0]['firmaaaprobado']."</td>
					<td class='celda1' style='font-size:9px;text-align:center;height:60px;border-bottom:0px solid white;'>".$dato_cabecera_OP[0]['RecibNomApe']."<br>".$dato_cabecera_OP[0]['firmarecibido']."</td>
				</tr>
				<tr>
					<td style='font-size:7px;text-align:center;border-left:1px solid black;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
					<td style='font-size:7px;text-align:center;border-right:1px solid black;border-top:0px solid white;'>Firma digital desde NETSUITE</td>
				</tr>
				<tr>
					<td class='celda1' style='font-size:9px;text-align:center;'>Asistente IDE</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Asistente IDE</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Logística</td>
					<td class='celda1' style='font-size:9px;text-align:center;'>Almacén</td>
				</tr>
			</table>
		");
		
		$mpdf->Ln(3);
		
		$mpdf->WriteHTML("
			<table class='tabla2' width='100%'>
				<tr>
					<td colspan=4 style='font-size:11px;'><strong>Observaciones</strong></td>
				</tr>
				<tr>
					<td colspan=4 style='font-size:10px;'>".$dato_cabecera_OP[0]['Nota']."</td>
				</tr>
			</table>
		");
		
		header('Access-Control-Allow-Origin: *');
		
		$fecha = date("YmdHis",time());
		
		$archivo= "downloads/OT_".$dato_cabecera_OP[0]['idOP']."_".$fecha.".pdf";

		$mpdf->Output($archivo, 'F');  //D: descarga directa, I: visualizacion, F: descarga en ruta especifica
		
		if (file_exists($archivo)) {
			$msg="ok";
			$file=$archivo;
		} else {
			$msg="no";
			$file="";
		}
		
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"msg"=>$msg,
			"file"=>$file,
		]);
	}
	
	/*public function hola(){
		//require_once '../Classes/PHPExcel.php';
		
		$this->getLibrary('PHPExcel/Classes/PHPExcel');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');

		// Crea un nuevo objeto PHPExcel
		$objPHPExcel = new PHPExcel();
		 
		// Establecer propiedades
		$objPHPExcel->getProperties()
			->setCreator("Cattivo")
			->setLastModifiedBy("Cattivo")
			->setTitle("Documento Excel de Prueba")
			->setSubject("Documento Excel de Prueba")
			->setDescription("Demostracion sobre como crear archivos de Excel desde PHP.")
			->setKeywords("Excel Office 2007 openxml php")
			->setCategory("Pruebas de Excel");

		 

		// Agregar Informacion
		$objPHPExcel->setActiveSheetIndex(0)
			->setCellValue('A1', 'Valor 1')
			->setCellValue('B1', 'Valor 2')
			->setCellValue('C1', 'Total')
			->setCellValue('A2', '10')
			->setCellValue('C2', '=sum(A2:B2)');

		// Renombrar Hoja
		$objPHPExcel->getActiveSheet()->setTitle('Tecnologia Simple');

		 
		// Establecer la hoja activa, para que cuando se abra el documento se muestre primero.
		$objPHPExcel->setActiveSheetIndex(0);

		 

		// Se modifican los encabezados del HTTP para indicar que se envia un archivo de Excel.
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="pruebaReal.xlsx"');
		header('Cache-Control: max-age=0');

		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		$objWriter->save('php://output');

		exit;
	}*/
	
	public function descargaExcel(){
		
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		
		/*header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(["input"=>$input]);*/
		
		$this->getLibrary('PHPExcel/Classes/PHPExcel');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel5');
        $this->getLibrary('PHPExcel/Classes/PHPExcel/Reader/Excel2007');
		
		$objPHPExcel = new PHPExcel();
		
		$objPHPExcel->getProperties()
							->setCreator("Laboratorios Biomont") //Autor
							->setLastModifiedBy("Laboratorios Biomont") //Ultimo usuario que lo modificó
							->setTitle("Reporte de Traslado de Inventario")
							->setSubject("Reporte de Traslado de Inventario") //Asunto
							->setDescription("Reporte de Traslado de Inventario")//Descripción
							->setKeywords("Reporte de Traslado de Inventario") //Etiquetas
                            ->setCategory("Reporte excel");  //Categorias
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A1','TRASLADO DE INVENTARIO '.$input['dato']['idTI'])
                    ->mergeCells('A1:H1');

        $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A2','')
                    ->mergeCells('A2:H2');
					
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A3','')
                    ->mergeCells('A3:H3');
		
		$objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue('A4','')
                    ->mergeCells('A4:H4');
					
		$objPHPExcel->getActiveSheet()->setShowGridlines(true);
		
		$estilos_titulo = array(
                'font' => array(
                    'name'      => 'Verdana',
                    'bold'      => true, 
                    'size'      => 11,
                    'color'     => array(
                        'rgb' => '000000'
                    )
                ),
                'fill' 	=> array(
                    'type'		=> PHPExcel_Style_Fill::FILL_GRADIENT_LINEAR,
                    'rotation'   => 90,
                    'startcolor' => array(
                        'rgb' => 'eaecef'
                    ),
                    'endcolor'   => array(
                        'argb' => 'eaecef'
                    )
                ),
                'borders' => array(
                    'top'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => 'FFFFFF'
                        )
                    ),
                    'bottom'     => array(
                        'style' => PHPExcel_Style_Border::BORDER_THIN ,
                        'color' => array(
                            'rgb' => 'FFFFFF'
                        )
                    )
                ),
                'alignment' =>  array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        'wrap'       => TRUE
                )
        );

		$estilos_cabeceras = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => true,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 8,
	            'color'     => array('rgb' => '000000')
				
            ),
	       'fill' 	=> array(
							'type'		 => PHPExcel_Style_Fill::FILL_SOLID,
							'rotation'   => 90,
							'startcolor' => array('rgb' => 'eaecef'),
							'endcolor'   => array('rgb' => 'eaecef')
			),
            'borders' => array(
								'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 
								'alignment' =>  array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
													'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'rotation'   => 0,
													'wrap'       => TRUE
								)
			
        );
		
		$estilos_celdas = array(
        	'font' => array(
	        	'name'      => 'Verdana',
    	        'bold'      => false,
        	    'italic'    => false,
                'strike'    => false,
               	'size'      => 8,
	            'color'     => array('rgb' => '000000')
				
            ),
            'borders' => array(
								'allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN)), 
								'alignment' =>  array(
													'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_GENERAL,
													'vertical'   => PHPExcel_Style_Alignment::VERTICAL_CENTER,
													'rotation'   => 0,
													'wrap'       => FALSE
								)
			
        );
		
		$objModel = $this->loadModel("index");
		$data_cab = $objModel->getHead(intval($input['dato']['idTI']));
		//$data_cab = $objModel->getHead(intval($numero));
		$data_det = $objModel->getArticulos(intval($data_cab[0]['idtransaccion']),intval($data_cab[0]['idOP']));

			
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A3', "NUMERO OT: ".$data_cab[0]['NroOP']);
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A4', "LINEA: ".$data_cab[0]['linea']);
	
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A5', "CODIGO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('B5', "DESCRIPCION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('C5', "LOTE");
		$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D5', "POTENCIA/VERSION");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('E5', "ANALISIS");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('F5', "F. INGRESO");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('G5', "F. EXPIRA");
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('H5', "PRINCIPO ACTIVO");
		
		$i=6;
		foreach($data_det as $det){
			$new_dato = explode("#",$det['numserielote']);
			if($det['principActivo']=="T"){
				$principio = "PRINCIPIO ACTIVO";
			}else{
				$principio = "";
			}
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $det['codarticulo']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $det['nomarticulo']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $new_dato[0]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $new_dato[1]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $new_dato[2]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $det['fechacreacion']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, $det['fechacaducidad']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('H'.$i, $principio);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':H'.$i)->applyFromArray($estilos_celdas);
			$objPHPExcel->getDefaultStyle()->getAlignment('A'.$i.':H'.$i)->setWrapText(true);

			$i++;
		}
		
		/*$c=0;
		for($i=6;$i<count($data_det);$i++){
			
			$new_dato = explode(";",$data_det[$c]['numserielote']);
			
			if($data_det[$c]['principActivo']=="T"){
				$principio = "PRINCIPIO ACTIVO";
			}else{
				$principio = "EXCIPIENTES";
			}
			
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('A'.$i, $data_det[$c]['codarticulo']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('B'.$i, $data_det[$c]['nomarticulo']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('C'.$i, $new_dato[0]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('D'.$i, $new_dato[1]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('E'.$i, $new_dato[2]);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('F'.$i, $data_det[$c]['fechacaducidad']);
			$objPHPExcel->setActiveSheetIndex(0)->setCellValue('G'.$i, $principio);
			
			$objPHPExcel->getActiveSheet()->getStyle('A'.$i.':G'.$i)->applyFromArray($estilos_celdas);
			$objPHPExcel->getDefaultStyle()->getAlignment('A'.$i.':G'.$i)->setWrapText(true);
			
			$c++;
		}*/
	

		$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->applyFromArray($estilos_titulo);

        $objPHPExcel->getActiveSheet()->getStyle('A5:H5')->applyFromArray($estilos_cabeceras);
		
		//$objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(40);
		
		$archivo = "TI_".$input['dato']['idTI']."_".date('dmYHis').".xlsx";
		
		$objPHPExcel->getActiveSheet()->setTitle('TRASLADO DE INVENTARIO');
		
		$objPHPExcel->setActiveSheetIndex(0);
		
		//$objPHPExcel->getActiveSheet(0)->freezePane('A4');
		
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$archivo);
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');

		ob_start();
        $objWriter->save("php://output");
        $xlsData = ob_get_contents();
        ob_end_clean();

        $response =  [
					'op' => 'ok',
					'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
					'nombre' => $archivo
				];
		
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode($response);

	}
	
	public function obtenerArticulos(){
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("index");
		$articulos = $objModel->cargarArticulos(intval($input['dato']['idArt']));
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"resultado"=>$articulos,
		]);
	}
	
	public function obtenerArticulos_AJUSTE(){
		header('Access-Control-Allow-Origin: *');
		
		$input = json_decode(file_get_contents("php://input"), true);
		
		$objModel = $this->loadModel("index");
		$articulos = $objModel->cargarArticulos_AJUSTE(intval($input['dato']['idArt']));
		
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"resultado"=>$articulos,
		]);
	}
	
	public function imprimirEtiquetaSalida($idarticulo,$traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP($traslado);
		$dato = $objModel->getDatosEtiquetaSalida($res[0]['idtraslado'],$idarticulo);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$mpdf->SetDefaultFont("Arial");

			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:15%;'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
						</td>
						<td style='width:75%;' align='center'>
							<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
						</td>
						<td style='width:10%;'>
							F-AL.009.01
						</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(1);
			
			$numserielote = explode("#",$dat['numserielote']);
			
			if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
				$fecha_caducidad = "";
			}else{
				$fecha_caducidad = $dat['fechacaducidad'];
			}
			
			$index = strpos($dat['codarticulo'], "0");
			$cod_letra = substr($dat['codarticulo'],0,$index);
			
			switch($cod_letra){
				case 'ME':
				case 'MV':
					$version_fecha = $numserielote[1];
					break;
				default:
					$version_fecha = $fecha_caducidad;
					break;
			}
			
			date_default_timezone_set('America/Lima');
			
			$mpdf->WriteHTML("
				<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>MATERIAL</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".$dat['codarticulo']." ".$dat['nomarticulo']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>LOTE</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$numserielote[0]."</td>
						<td style='width:42%;font-size: 12px;padding:5px;font-weight:bold;' colspan=2>N° ANALISIS</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>F. VEN</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$version_fecha."</td>
						<td style='width:42%;font-size: 12px;padding:5px;' colspan=2>".$numserielote[2]."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>P/BRUTO</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						<td style='width:25%;border-top: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>PESADO POR</td>
						<td style='width:22%;border-top: 1px solid black;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>V°B°</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>TARA</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						<td style='width:25%;font-size: 12px;padding:5px;'></td>
						<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>CANTIDAD</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".number_format($dat['cantidad'],4)." ".$dat['unidad']."</td>
						<td style='width:25%;font-size: 12px;padding:5px;'></td>
						<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>FECHA</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".date("d/m/Y",time())."</td>
					</tr>
					<tr>
						<td style='width:100%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=4><span style='font-weight:bold;'>OBSERVACION:</span> ".$res[0]['observacion']."</td>
					</tr>
				</table>
			");
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		
		}
	
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalidaTodo($traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo($res[0]['idtraslado']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$mpdf->SetDefaultFont("Arial");

			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:15%;'>
							<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
						</td>
						<td style='width:75%;' align='center'>
							<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
						</td>
						<td style='width:10%;'>
							F-AL.009.01
						</td>
					</tr>
				</table>
			");
			
			$mpdf->Ln(1);
			
			$numserielote = explode("#",$dat['numserielote']);
			
			if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
				$fecha_caducidad = "";
			}else{
				$fecha_caducidad = $dat['fechacaducidad'];
			}
			
			$index = strpos($dat['codarticulo'], "0");
			$cod_letra = substr($dat['codarticulo'],0,$index);
			
			switch($cod_letra){
				case 'ME':
				case 'MV':
					$version_fecha = $numserielote[1];
					break;
				default:
					$version_fecha = $fecha_caducidad;
					break;
			}
			
			date_default_timezone_set('America/Lima');
			
			$mpdf->WriteHTML("
				<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>MATERIAL</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".$dat['codarticulo']." ".$dat['nomarticulo']."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>LOTE</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$numserielote[0]."</td>
						<td style='width:42%;font-size: 12px;padding:5px;font-weight:bold;' colspan=2>N° ANALISIS</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>F. VEN</td>
						<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$version_fecha."</td>
						<td style='width:42%;font-size: 12px;padding:5px;' colspan=2>".$numserielote[2]."</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>P/BRUTO</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						<td style='width:25%;border-top: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>PESADO POR</td>
						<td style='width:22%;border-top: 1px solid black;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>V°B°</td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>TARA</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						<td style='width:25%;font-size: 12px;padding:5px;'></td>
						<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>CANTIDAD</td>
						<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".number_format($dat['cantidad'],4)." ".$dat['unidad']."</td>
						<td style='width:25%;font-size: 12px;padding:5px;'></td>
						<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
					</tr>
					<tr>
						<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>FECHA</td>
						<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".date("d/m/Y",time())."</td>
					</tr>
					<tr>
						<td style='width:100%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=4><span style='font-weight:bold;'>OBSERVACION:</span> ".$res[0]['observacion']."</td>
					</tr>
				</table>
			");
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}		
		
		}
	
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalida_AJUSTE($idarticulo,$traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalida_AJUSTE($res[0]['idtraslado'],$idarticulo);
		
		$cont = 0;
		foreach($dato as $dat){
			
			if(intval($dat['cantidad'])>=0){

				
				$inventorynumber = explode("#",$dat['numserielote']);
			
				$index = strpos($dat['codarticulo'], "0");
				$cod_letra = substr($dat['codarticulo'],0,$index);
				
				if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
					$fecha_exp = "";
				}else{
					$fecha_exp = $dat['fechacaducidad'];
				}
				
				switch($cod_letra){
					case 'MP':
					case 'MMP':
						$lote = $inventorynumber[0];
						//$v_potencia = "";
						$num_analisis = $inventorynumber[2];
						//$peso = $inventorynumber[3];
						$fecha_ven_poten = $fecha_exp;
						break;
					default:
						$lote = $inventorynumber[0];
						//$v_potencia = $inventorynumber[1];
						$num_analisis = $inventorynumber[2];
						//$peso = "";
						$fecha_ven_poten = $inventorynumber[1];
						break;
				}
				
				$mpdf->SetDefaultFont("Arial");
				
				$mpdf->WriteHTML("
					<table width='100%'>
						<tr>
							<td style='width:15%;'>
								<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
							</td>
							<td style='width:75%;' align='center'>
								<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
							</td>
							<td style='width:10%;'>
								F-AL.001
							</td>
						</tr>
					</table>
				");
				
				$mpdf->Ln(1);
				
				$mpdf->WriteHTML("
					<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>NRO DOC</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['TranID']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>FECHA</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['trandate']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>PROVEEDOR</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['entityid']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CODIGO</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['codarticulo']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DUA</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['dua']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DESCRIPCION</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['nomarticulo']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANTIDAD</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['cantidad']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>UND</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['abbreviation']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>LOTE</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$lote."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>F.VEN / VER</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$fecha_ven_poten."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>OBSERVACION</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['memo']."</td>
						</tr>
					</table>
				");

				$footer = "_________________________________________________________";
				$footer.= "<div align='center'><span style='font-size:23px;font-weight:bold;'>ALMACEN DE ".strtoupper($dat['name'])."</span></div>";

				$mpdf->SetHTMLFooter($footer);
				
				$cont++;
				
				if($cont < count($dato)){
					$mpdf->AddPage();
				}	
				
			}else{
		
				$mpdf->SetDefaultFont("Arial");

				$mpdf->WriteHTML("
					<table width='100%'>
						<tr>
							<td style='width:15%;'>
								<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
							</td>
							<td style='width:75%;' align='center'>
								<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
							</td>
							<td style='width:10%;'>
								F-AL.009
							</td>
						</tr>
					</table>
				");
				
				$mpdf->Ln(1);
				
				$numserielote = explode("#",$dat['numserielote']);
				
				if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
					$fecha_caducidad = "";
				}else{
					$fecha_caducidad = $dat['fechacaducidad'];
				}
				
				$index = strpos($dat['codarticulo'], "0");
				$cod_letra = substr($dat['codarticulo'],0,$index);
				
				switch($cod_letra){
					case 'ME':
					case 'MV':
						$version_fecha = $numserielote[1];
						break;
					default:
						$version_fecha = $fecha_caducidad;
						break;
				}
				
				date_default_timezone_set('America/Lima');
				
				$mpdf->WriteHTML("
					<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>MATERIAL</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".$dat['codarticulo']." ".$dat['nomarticulo']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>LOTE</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$numserielote[0]."</td>
							<td style='width:42%;font-size: 12px;padding:5px;font-weight:bold;' colspan=2>N° ANALISIS</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>F. VEN</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$version_fecha."</td>
							<td style='width:42%;font-size: 12px;padding:5px;' colspan=2>".$numserielote[2]."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>P/BRUTO</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
							<td style='width:25%;border-top: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>PESADO POR</td>
							<td style='width:22%;border-top: 1px solid black;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>V°B°</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>TARA</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
							<td style='width:25%;font-size: 12px;padding:5px;'></td>
							<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>CANTIDAD</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".number_format($dat['cantidad']*-1,4)." ".$dat['unidad']."</td>
							<td style='width:25%;font-size: 12px;padding:5px;'></td>
							<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>FECHA</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".date("d/m/Y",time())."</td>
						</tr>
						<tr>
							<td style='width:100%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=4><span style='font-weight:bold;'>OBSERVACION:</span> ".$res[0]['observacion']."</td>
						</tr>
					</table>
				");
				
				$cont++;
				
				if($cont < count($dato)){
					$mpdf->AddPage();
				}		
					
			}
			
		}
	
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalida_AJUSTE_materiaprima($idarticulo,$traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA MATERIA PRIMA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalida_AJUSTE_materiaprima($res[0]['idtraslado'],$idarticulo);
		
		$cont = 0;
		foreach($dato as $dat){

			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = "";
					break;
			}
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			if($dat['fec_analisis']==null || $dat['fec_analisis']=="" || $dat['fec_analisis']=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = $dat['fec_analisis'];
			}
			
			if($dat['fecha_analisis']==null || $dat['fecha_analisis']=="" || $dat['fecha_analisis']=="01/01/1970"){
				$fecha_analisis = "";
			}else{
				$fecha_analisis = $dat['fecha_analisis'];
			}
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.001.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIA PRIMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$lote."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOC.</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>N° ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$num_analisis."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['quantity']." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_analisis."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>POTENCIA T/C</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$v_potencia."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>PESO ESPECIFICO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$peso."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}	
		}
		
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalida_AJUSTE_materialempaqueenvase($idarticulo,$traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA MATERIAL EMPAQUE Y ENVASE');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo_AJUSTE_materialempaqueenvase($res[0]['idtraslado'],$idarticulo);
		
		$cont = 0;
		foreach($dato as $dat){
			
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = "";
					break;
			}

			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}			
			
			if(explode('|',$dat['arreglo'])[1]==null || explode('|',$dat['arreglo'])[1]=="" || explode('|',$dat['arreglo'])[1]=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = explode('|',$dat['arreglo'])[1];
			}
			
			switch($dat['undPrincipal']){
				case 'NIU':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					//$cant_aprobada = explode('|',$dat['arreglo'])[2];
					break;
				case 'KGM':
					if($dat['TranID']=='823'){
						$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					}else{
						$cant_aprobada = explode('|',$dat['arreglo'])[2];
					}
					break;
				case 'MLL':
				case 'MIL':
				case 'GRM':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]*1000);
					break;
				case 'GLL':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]/3.8);
					break;
				case 'LTR':
					$cant_aprobada = floatval(explode('|',$dat['arreglo'])[2]);
					break;	
			}
			
			$cant_aprobada = str_replace(",","",$cant_aprobada);
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.002.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIAL DE EMPAQUE / ENVASE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$cant_aprobada." ".$dat['abbreviation']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA:</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_ven."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>VERSION</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$version."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>OBSERVACIONES</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:5%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:45%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:5%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:5%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:45%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:5%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}	
		}
		
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalida_AJUSTE_rechazo($idarticulo,$traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA RECHAZADO');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalida_AJUSTE_rechazo($res[0]['idtraslado'],$idarticulo);
		
		$cont = 0;
		foreach($dato as $dat){
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					break;
			}			
			
			if($dat['fec_analisis']==null || $dat['fec_analisis']=="" || $dat['fec_analisis']=="01/01/1970"){
				$fecha_analisis = "";
			}else{
				$fecha_analisis = $dat['fec_analisis'];
			}
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.007.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								RECHAZADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:46%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:3%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:20%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. RECHAZADA</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['quantity']." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$fecha_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>MOTIVO RECHAZO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}
		}
		
		$mpdf->Output();
	}
	
	public function imprimirEtiquetaSalidaTodo_AJUSTE($traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo_AJUSTE($res[0]['idtraslado']);
		
		$cont = 0;
		foreach($dato as $dat){
			
			if(intval($dat['cantidad'])>=0){

				
				$inventorynumber = explode("#",$dat['numserielote']);
			
				$index = strpos($dat['codarticulo'], "0");
				$cod_letra = substr($dat['codarticulo'],0,$index);
				
				if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
					$fecha_exp = "";
				}else{
					$fecha_exp = $dat['fechacaducidad'];
				}
				
				switch($cod_letra){
					case 'MP':
					case 'MMP':
						$lote = $inventorynumber[0];
						//$v_potencia = "";
						$num_analisis = $inventorynumber[2];
						//$peso = $inventorynumber[3];
						$fecha_ven_poten = $fecha_exp;
						break;
					default:
						$lote = $inventorynumber[0];
						//$v_potencia = $inventorynumber[1];
						$num_analisis = $inventorynumber[2];
						//$peso = "";
						$fecha_ven_poten = $inventorynumber[1];
						break;
				}
				
				$mpdf->SetDefaultFont("Arial");
				
				$mpdf->WriteHTML("
					<table width='100%'>
						<tr>
							<td style='width:15%;'>
								<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
							</td>
							<td style='width:75%;' align='center'>
								<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
							</td>
							<td style='width:10%;'>
								F-AL.001
							</td>
						</tr>
					</table>
				");
				
				$mpdf->Ln(1);
				
				$mpdf->WriteHTML("
					<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>NRO DOC</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['TranID']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>FECHA</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['trandate']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>PROVEEDOR</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['entityid']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CODIGO</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['codarticulo']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DUA</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['dua']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>DESCRIPCION</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['nomarticulo']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>CANTIDAD</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['cantidad']."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>UND</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$dat['abbreviation']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>LOTE</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$lote."</td>
							<td style='width:20%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>F.VEN / VER</td>
							<td style='width:22%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;'>".$fecha_ven_poten."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;font-weight:bold;'>OBSERVACION</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 11px;padding:4.5px 3px 5px 3px;' colspan=3>".$dat['memo']."</td>
						</tr>
					</table>
				");

				$footer = "_________________________________________________________";
				$footer.= "<div align='center'><span style='font-size:23px;font-weight:bold;'>ALMACEN DE ".strtoupper($dat['name'])."</span></div>";

				$mpdf->SetHTMLFooter($footer);
				
				$cont++;
				
				if($cont < count($dato)){
					$mpdf->AddPage();
				}	
				
			}else{
		
				$mpdf->SetDefaultFont("Arial");

				$mpdf->WriteHTML("
					<table width='100%'>
						<tr>
							<td style='width:15%;'>
								<img src='http://6462530.shop.netsuite.com/core/media/media.nl?id=4499&c=6462530&h=s4vSW99RWLBxlBgfKjmRz8LD0h85_fj5MMZjCgrJwYDFq4v7' width='67' height='27'>
							</td>
							<td style='width:75%;' align='center'>
								<span style='font-size:16px;'><strong>LABORATORIOS BIOMONT S.A.</strong></span>
							</td>
							<td style='width:10%;'>
								F-AL.009.01
							</td>
						</tr>
					</table>
				");
				
				$mpdf->Ln(1);
				
				$numserielote = explode("#",$dat['numserielote']);
				
				if($dat['fechacaducidad']==null || $dat['fechacaducidad']=="" || $dat['fechacaducidad']=="01/01/1970"){
					$fecha_caducidad = "";
				}else{
					$fecha_caducidad = $dat['fechacaducidad'];
				}
				
				$index = strpos($dat['codarticulo'], "0");
				$cod_letra = substr($dat['codarticulo'],0,$index);
				
				switch($cod_letra){
					case 'ME':
					case 'MV':
						$version_fecha = $numserielote[1];
						break;
					default:
						$version_fecha = $fecha_caducidad;
						break;
				}
				
				date_default_timezone_set('America/Lima');
				
				$mpdf->WriteHTML("
					<table width='100%' style='border: 1px solid black;border-collapse: collapse;'>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>MATERIAL</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".$dat['codarticulo']." ".$dat['nomarticulo']."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>LOTE</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$numserielote[0]."</td>
							<td style='width:42%;font-size: 12px;padding:5px;font-weight:bold;' colspan=2>N° ANALISIS</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>F. VEN</td>
							<td style='width:35%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".$version_fecha."</td>
							<td style='width:42%;font-size: 12px;padding:5px;' colspan=2>".$numserielote[2]."</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>P/BRUTO</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
							<td style='width:25%;border-top: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>PESADO POR</td>
							<td style='width:22%;border-top: 1px solid black;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>V°B°</td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>TARA</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
							<td style='width:25%;font-size: 12px;padding:5px;'></td>
							<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>CANTIDAD</td>
							<td style='width:30%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'>".number_format($dat['cantidad']*-1,4)." ".$dat['unidad']."</td>
							<td style='width:25%;font-size: 12px;padding:5px;'></td>
							<td style='width:22%;border-left: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;'></td>
						</tr>
						<tr>
							<td style='width:23%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;font-weight:bold;'>FECHA</td>
							<td style='width:77%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=3>".date("d/m/Y",time())."</td>
						</tr>
						<tr>
							<td style='width:100%;border: 1px solid black;border-collapse: collapse;font-size: 12px;padding:5px;' colspan=4><span style='font-weight:bold;'>OBSERVACION:</span> ".$res[0]['observacion']."</td>
						</tr>
					</table>
				");
				
				$cont++;
				
				if($cont < count($dato)){
					$mpdf->AddPage();
				}		
					
			}	
		
		}
	
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalidaTodo_AJUSTE_materiaprima($traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA MATERIA PRIMA');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo_AJUSTE_materiaprima($res[0]['idtraslado']);
		
		$cont = 0;
		foreach($dato as $dat){
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = "";
					break;
			}
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			if($dat['fec_analisis']==null || $dat['fec_analisis']=="" || $dat['fec_analisis']=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = $dat['fec_analisis'];
			}
			
			if($dat['fecha_analisis']==null || $dat['fecha_analisis']=="" || $dat['fecha_analisis']=="01/01/1970"){
				$fecha_analisis = "";
			}else{
				$fecha_analisis = $dat['fecha_analisis'];
			}
		
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.001.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIA PRIMA &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:75%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$lote."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOC.</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>N° ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$num_analisis."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['quantity']." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_analisis."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:24%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>POTENCIA T/C</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$v_potencia."</td>
						<td style='width:32%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>PESO ESPECIFICO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$peso."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
		
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}	
		}
		
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalidaTodo_AJUSTE_materialempaqueenvase($traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA SALIDA MATERIAl EMPAQUE Y ENVASE');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo_AJUSTE_materialempaqueenvase($res[0]['idtraslado']);
		
		$cont = 0;
		foreach($dato as $dat){
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					$v_potencia = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					$peso = "";
					break;
			}
			
			if($dat['expirationdate']==null || $dat['expirationdate']=="" || $dat['expirationdate']=="01/01/1970"){
				$fecha_exp = "";
			}else{
				$fecha_exp = $dat['expirationdate'];
			}
			
			if($dat['fec_analisis']==null || $dat['fec_analisis']=="" || $dat['fec_analisis']=="01/01/1970"){
				$fecha_ven = "";
			}else{
				$fecha_ven = $dat['fec_analisis'];
			}
			
			if($dat['fecha_analisis']==null || $dat['fecha_analisis']=="" || $dat['fecha_analisis']=="01/01/1970"){
				$fecha_analisis = "";
			}else{
				$fecha_analisis = $dat['fecha_analisis'];
			}
		
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.002.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								APROBADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								MATERIAL DE EMPAQUE / ENVASE&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=2>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. APROBADA</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['quantity']." ".$dat['abbreviation']."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. EXPIRA:</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_exp."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:28%;font-size: 11px;padding:2px 2px 2px 2px;'>".$fecha_ven."</td>
						<td style='width:21%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>VERSION</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:22%;font-size: 11px;padding:2px 2px 2px 2px;'>".$v_potencia."</td>
					</tr>
					<tr>
						<td style='width:29%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>OBSERVACIONES</td>
						<td style='width:1%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:70%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memo']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
		
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}	
		}
		
		$mpdf->Output();
		
	}
	
	public function imprimirEtiquetaSalidaTodo_AJUSTE_rechazo($traslado){
		
		header('Access-Control-Allow-Origin: *');
		
		$this->getLibrary('mpdf/mpdf');

		$mpdf = new mPDF('utf-8', 'TICKET', '', '', 2, 2, 2, 0, 1, 3);

		$mpdf->SetTitle('IMPRESION DE ETIQUETA RECHAZADO');
		
		$objModel = $this->loadModel("index");
		$res = $objModel->getIdTrasladoOP_AJUSTE($traslado);
		$dato = $objModel->getDatosEtiquetaSalidaTodo_AJUSTE_rechazo($res[0]['idtraslado']);
		
		$cont = 0;
		foreach($dato as $dat){
			$inventorynumber = explode("#",$dat['inventorynumber']);
			
			$index = strpos($dat['itemid'], "0");
			$cod_letra = substr($dat['itemid'],0,$index);
			
			switch($cod_letra){
				case 'MP':
				case 'MMP':
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = $inventorynumber[3];
					break;
				default:
					$lote = $inventorynumber[0];
					//$version = $inventorynumber[1];
					$num_analisis = $inventorynumber[2];
					//$peso = "";
					break;
			}			
			
			if($dat['fec_analisis']==null || $dat['fec_analisis']=="" || $dat['fec_analisis']=="01/01/1970"){
				$fecha_analisis = "";
			}else{
				$fecha_analisis = $dat['fec_analisis'];
			}
			
			$mpdf->SetDefaultFont("Arial");
			
			$mpdf->WriteHTML("<div style='position:absolute;font-size:7px;font-weight:bold;'>
								LABORATORIOS BIOMONT S.A.
							</div>
							<div style='font-size:7px;font-weight:bold;text-align:right;'>
								F-CC.007.03
							</div>
							<div align='center' style='font-size:42px;font-weight:bold;padding:0px;'>
								RECHAZADO
							</div>
							<div align='right' style='font-size:8px;font-weight:bold;'>
								&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;CONTROL DE CALIDAD
							</div>
							");
			
			$mpdf->Ln(1);
			
			$mpdf->WriteHTML("
				<table width='100%'>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$num_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NRO DOCUMENTO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['TranID']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>NOMBRE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['description']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CODIGO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:46%;font-size: 11px;padding:2px 2px 2px 2px;'>".$dat['itemid']."</td>
						<td style='width:3%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>LOTE</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:20%;font-size: 11px;padding:2px 2px 2px 2px;'>".$lote."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>CANT. RECHAZADA</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['quantity']." ".$dat['abbreviation']."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>FEC. ANALISIS</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$fecha_analisis."</td>
					</tr>
					<tr>
						<td style='width:30%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>MOTIVO RECHAZO</td>
						<td style='width:0.5%;font-size: 11px;font-weight:bold;padding:2px 2px 2px 2px;'>:</td>
						<td style='width:69.5%;font-size: 11px;padding:2px 2px 2px 2px;' colspan=4>".$dat['memoControl']."</td>
					</tr>
				</table>
			");
			
			$footer = "<table width='100%' nowrap>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><hr style='width:100%;color:black;' /></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
						   <tr>
							 <td style='width:15%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>ANALISTA</strong></td>
							 <td style='width:20%;font-size: 8px;'></td>
							 <td align='center' style='width:25%;font-size: 8px;'><strong>JEFE DE CC</strong></td>
							 <td style='width:15%;font-size: 8px;'></td>
						   </tr>
					 </table>";
					 
			$mpdf->SetHTMLFooter($footer);
			
			$cont++;
			
			if($cont < count($dato)){
				$mpdf->AddPage();
			}
		}
		
		$mpdf->Output();
		
	}
	
	/* CONSULTA MYSQL */
	/*public function queryMysql(){
		$objModel = $this->loadModel("index");
		$dato_articulos = $objModel->getArticulos('85540','80505');
		
		$datos_mysql = $objModel->queryMysql('80505');
		
		$res = $this->custom_array_merge($dato_articulos, $datos_mysql);
		
		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"res"=>$res,
			//"res1"=>$datos_mysql,
		]);
	}*/
	
	/* Funcion INNER JOIN en php*/
	
	function custom_array_merge($array1, $array2) {
		$result = Array();
		foreach ($array1 as $key_1 => $value_1) {
			// if($value['name'])
			foreach ($array2 as $key_1 => $value_2) {
				if($value_1['codarticulo'] ==  $value_2['componente_mysql']) {
					$result[] = array_merge($value_1,$value_2);
				}
			}
		}
		return $result;
	}
	
	/*function custom_array_merge($a, $b) {
		foreach ($a as $indice =>$value){
			if ($a[$indice] != $b[$indice])
				$b[$indice]=$value;
		}
		return $b;
	}*/
	
	/* TEST */
	/*public function consultaOracle(){
		$data = array();
		$data =  [
			[
				"id"=>1,
				"nombre"=>"Juan",
				"apellido"=>"Peña"
			],
			[
				"id"=>2,
				"nombre"=>"Frank",
				"apellido"=>"Castro"
			]
		];

		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode([
			"res"=>$data
		]);
		
	}
	
	public function consultaOracle1(){
		$data = array();
		$data =  [1,2,3,4];

		header('Access-Control-Allow-Origin: *');
		header('Content-type: application/json; charset=utf-8');
		echo json_encode(
			$data
		);
		
	}*/
}
