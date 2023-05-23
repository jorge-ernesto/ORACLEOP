<?php

class indexModel extends Model
{

	public function __construct()
	{
		parent::__construct();
	}
	//WHERE TRX.abbrevtype='WORKORD' and TRX.TranID IN ('$op');";
	public function getTraslados($op)
	{
		$sql = "SELECT 
		T.TranID AS NroTraslado,
		T.trandate AS FechaCreacion, 
		T.trandisplayname AS NombreTraslado, 
		T.custbody44 AS codarticulo, 
		I.description AS nomarticulo
		FROM TRANSACTION T
		LEFT JOIN (SELECT id,abbrevtype,TranID FROM TRANSACTION) TRX ON TRX.id=T.custbody42
		LEFT JOIN (SELECT itemid,description FROM ITEM) I ON I.itemid=T.custbody44
		WHERE TRX.TranID IN ('$op');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"NroTraslado"	 => $rs->fields[0],
					"FechaCreacion"	 => date('d/m/Y',strtotime($rs->fields[1])),
					"NombreTraslado" => $rs->fields[2],
					"codarticulo"	 => $rs->fields[3],
					"nomarticulo"	 => $rs->fields[4]
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	//WHERE T.abbrevtype='INV TRFR' AND T.TranID IN ('$nroTraslado');";
	//(SELECT custbody41 FROM TRANSACTION where abbrevtype='WORKORD' and id=T.custbody42) as linea
	//T.custbody45 AS feccaducidad,
	public function getHead($nroTraslado){
		$sql = "SELECT 
		T.ID AS idtransaccion,
		T.TranID AS NroTraslado,
		TRX.TranID AS NroOP, 
		T.createddate AS FechaHoraCreacion, 
		T.trandate AS FechaCreacion, 
		TRX.trandate AS FechaCreacion_OT, 
		T.trandisplayname AS NombreTraslado,
		U.pluralabbreviation AS Unidad,
		T.memo AS Nota,
		OPT.name AS TipoOperacion, 
		T.custbody42 AS idOP,
		TRX.trandisplayname AS nomoperaciones,
		T.custbody43 AS Lote,
		T.custbody44 AS codarticulo,
		I.description AS nomarticulo,
		TRX.custbodybio_cam_fechacaducidad AS feccaducidad,
		TRX.custbody126 AS fecfabricacion,
		T.custbody47 AS canprod,
		LOWER(E1.entityid) AS NomDescarga,
		T.custbody71 AS fecfirmaDescarga,
		LOWER(E2.entityid) AS NomVerifica,
		T.custbody74 AS fecfirmaVerifica,
		(SELECT custbody41 FROM TRANSACTION where id=T.custbody42) as linea,
		(CASE 
			WHEN T.custbody125='1' THEN 'TOTAL' 
			WHEN T.custbody125='2' THEN 'PARCIAL 1' 
			WHEN T.custbody125='3' THEN 'PARCIAL 2'
			WHEN T.custbody125='4' THEN 'PARCIAL 3'
			WHEN T.custbody125='5' THEN 'ADICIONAL'
		END) as tipoTraslado
		FROM TRANSACTION T
		LEFT JOIN customrecord_ns_pe_operation_type OPT ON OPT.id=T.custbody_ns_pe_oper_type
		LEFT JOIN (SELECT itemid,description,consumptionunit FROM ITEM) I ON (I.itemid=T.custbody44)
		LEFT JOIN (SELECT id,trandisplayname,TranID,custbody126,custbodybio_cam_fechacaducidad,trandate  FROM TRANSACTION) TRX ON TRX.id=T.custbody42
		LEFT JOIN (SELECT id, entityid FROM EMPLOYEE ) E1 ON (E1.id=T.custbody75)
		LEFT JOIN (SELECT id, entityid FROM EMPLOYEE ) E2 ON (E2.id=T.custbody68)
		LEFT JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		WHERE T.id IN ('$nroTraslado');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	 	=> $rs->fields[0],
					"NroTraslado"	 	=> $rs->fields[1],
					"NroOP" 			=> $rs->fields[2],
					"FechaHoraCreacion"	=> $rs->fields[3],
					"FechaCreacion"	 	=> date("d/m/Y",strtotime($rs->fields[4])),
					"FechaCreacion_OT"	=> date("d/m/Y",strtotime($rs->fields[5])),
					"NombreTraslado"	=> utf8_encode($rs->fields[6]),
					"Unidad"			=> $rs->fields[7],
					"Nota"	 			=> utf8_encode($rs->fields[8]),
					"TipoOperacion"	 	=> utf8_encode($rs->fields[9]),
					"idOP"	 			=> $rs->fields[10],
					"nomoperaciones"	=> utf8_encode($rs->fields[11]),
					"Lote"	 			=> $rs->fields[12],
					"codarticulo"	 	=> $rs->fields[13],
					"nomarticulo"	 	=> utf8_encode($rs->fields[14]),
					"feccaducidad"	 	=> date("d/m/Y",strtotime($rs->fields[15])),
					"fecfabricacion"	=> date("d/m/Y",strtotime($rs->fields[16])),
					"canprod"	 		=> $rs->fields[17],
					"NomDescarga"		=> utf8_encode($rs->fields[18]),
					"fecfirmaDescarga"	=> $rs->fields[19],
					"NomVerifica"	 	=> utf8_encode($rs->fields[20]),
					"fecfirmaVerifica"	=> $rs->fields[21],
					"linea"	 			=> utf8_encode($rs->fields[22]),
					"tipoTraslado"	 	=> $rs->fields[23]
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	//(case when U.abbreviation='GR' then PT.quantity*1000 else PT.quantity end) as cantordprod,
	//WHERE TL.linesequencenumber>0 and T.abbrevtype='WORKORD' and T.ID IN ('$idOP')
	public function getArticulos($idTraslado,$idOP){
		
			$sql="SELECT distinct 
			TL.transaction as idtransaccion,
			TL.item as iditem,
			'1' as iddetalle,
			I.itemid as codarticulo,
			I.description as nomarticulo,
			I.displayname as nomarticulo2,
			PT.abbreviation as unidad,
			I.totalquantityonhand as cantdisponible, 
			(case when PT.abbreviation='GR' then TL.quantity*1000 else TL.quantity end) as canttrasladar,
			PT.bomquantity*1000 as cantordprod,
			PT.tlbomquantity as cantListaMateriales,
			TL.id as iddetalle,
			INU.inventorynumber as numserielote,
			T1.D1 as desdedeposito,
			B.binnumber as paradeposito,
			T1.D2 as deestado,
			IST.name as aestado,
			INU.lastmodifieddate as fechacreacion,
			INU.expirationdate as fechacaducidad,
			(case when PT.abbreviation='GR' then IA.quantity*1000 else IA.quantity end) as cantidad,
			(select custcol17 from transactionline where itemsource='STOCK' and (custcol17 is not null) and item=I.ID) as cantGenerada,
			PT.custrecord184 as principActivo,
			T1.id as iddeposito,
			TL.linesequencenumber as secuencia 
			FROM TransactionLine TL
			INNER JOIN Item I ON ( I.ID = TL.Item )
			/*INNER JOIN unitsTypeUom U on (U.internalid=I.purchaseunit)*/
			INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
			INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
			INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
			LEFT JOIN bin B ON (B.id=IA.bin)
			LEFT JOIN (
				select TLX.transaction as P1 ,TLX.item as P2, IAX.quantity*-1 as P3, BX.binnumber as D1,ISTX.name as D2, IAX.id as id, INUX.id as invent
				from TransactionLine TLX
				INNER JOIN Item IX ON ( IX.ID = TLX.Item )
				/*INNER JOIN unitsTypeUom UX on (UX.internalid=IX.purchaseunit)*/
				INNER JOIN inventoryAssignment IAX on (IAX.transaction=TLX.transaction and IAX.transactionline=TLX.id)
				INNER JOIN inventoryNumber INUX ON (INUX.id=IAX.inventorynumber)
				INNER JOIN inventoryStatus ISTX ON (ISTX.id=IAX.inventorystatus)
				LEFT JOIN bin BX ON (BX.id=IAX.bin)
				where TLX.quantity<0 and TLX.transaction='$idTraslado'
				) T1 on (P1=TL.transaction and P2=TL.item and P3=IA.quantity and invent=INU.id)
			LEFT JOIN (
				select I.itemid, bcr.custrecord184, TL.quantity*-1 as quantity, bcr.bomquantity, U.abbreviation, TL.bomquantity as tlbomquantity
				FROM Transaction T
				INNER JOIN (SELECT * FROM TransactionLine where itemsource like 'STOCK%') TL ON ( TL.Transaction = T.ID )
				INNER JOIN Item I ON ( I.ID = TL.Item )
				INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
				LEFT JOIN bomrevisioncomponent bcr on (bcr.bomrevision=T.billofmaterialsrevision and bcr.item=TL.Item)
				WHERE TL.linesequencenumber>0  and T.ID IN ('$idOP')
				) PT on PT.itemid=I.itemid 
			where TL.quantity>0 and TL.transaction='$idTraslado'
			order by I.itemid asc;";
			$rs  = $this->_db->get_Connection()->Execute($sql);
			$contador = $rs->RecordCount();
			if (intval($contador) > 0) {
				while (!$rs->EOF) {
					$datos[] = [
						"idtransaccion"	=> $rs->fields[0],
						"iditem"	 	=> $rs->fields[1],
						"iddetalle"	 	=> $rs->fields[2],
						"codarticulo"	=> $rs->fields[3],
						"nomarticulo"	=> utf8_encode($rs->fields[4]),
						"nomarticulo2"	=> utf8_encode($rs->fields[5]),
						"unidad"	 	=> $rs->fields[6],
						"cantdisponible"=> $rs->fields[7],
						"canttrasladar"	=> $rs->fields[8],
						"cantordprod"	=> $rs->fields[9],
						"cantListaMateriales"=> $rs->fields[10],
						"iddetalle1"	=> $rs->fields[11],
						"numserielote"	=> $rs->fields[12],
						"desdedeposito"	=> $rs->fields[13],
						"paradeposito"	=> $rs->fields[14],
						"deestado"	 	=> $rs->fields[15],
						"aestado"	 	=> $rs->fields[16],
						"fechacreacion" => date("d/m/Y",strtotime($rs->fields[17])),
						"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[18])),
						"cantidad"	 	=> $rs->fields[19],
						"cantGenerada" 	=> $rs->fields[20],
						"principActivo" => $rs->fields[21],
						"iddeposito" 	=> $rs->fields[22],
						"secuencia" 	=> $rs->fields[23],
						"cantidad_mysql"=> $rs->fields[24],
					];
					$rs->MoveNext();
				}
			}
			return $datos;
	}
	
	public function getArticulos_ID($idTraslado)
	{
			$sql="SELECT distinct 
			TL.transaction as idtransaccion,
			TL.item as iditem,
			U.internalid as iddetalle,
			I.itemid as codarticulo,
			I.description as nomarticulo,
			I.displayname as nomarticulo2,
			U.pluralabbreviation as unidad,
			I.totalquantityonhand as cantdisponible, 
			(case when U.abbreviation='GR' then TL.quantity*1000 else TL.quantity end) as canttrasladar,
			INU.inventorynumber as numserielote,
			T1.D1 as desdedeposito,
			B.binnumber as paradeposito,
			T1.D2 as deestado,
			IST.name as aestado,
			INU.lastmodifieddate as fechacreacion,
			INU.expirationdate as fechacaducidad,
			(case when U.abbreviation='GR' then IA.quantity*1000 else IA.quantity end) as cantidad,
			(select custcol17 from transactionline where itemsource='STOCK' and (custcol17 is not null) and item=I.ID) as cantGenerada,
			T1.id as iddeposito,
			TL.linesequencenumber as secuencia 
			FROM TransactionLine TL
			INNER JOIN Item I ON ( I.ID = TL.Item )
			INNER JOIN unitsTypeUom U on (U.internalid=I.saleunit)
			INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
			INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
			INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
			LEFT JOIN bin B ON (B.id=IA.bin)
			INNER JOIN (
				select TLX.transaction as P1 ,TLX.item as P2, IAX.quantity*-1 as P3, BX.binnumber as D1,ISTX.name as D2, IAX.id as id
				from TransactionLine TLX
				INNER JOIN Item IX ON ( IX.ID = TLX.Item )
				INNER JOIN unitsTypeUom UX on (UX.internalid=IX.saleunit)
				INNER JOIN inventoryAssignment IAX on (IAX.transaction=TLX.transaction and IAX.transactionline=TLX.id)
				INNER JOIN inventoryNumber INUX ON (INUX.id=IAX.inventorynumber)
				INNER JOIN inventoryStatus ISTX ON (ISTX.id=IAX.inventorystatus)
				LEFT JOIN bin BX ON (BX.id=IAX.bin)
				where TLX.quantity<0
				) T1 on (P1=TL.transaction and P2=TL.item and P3=IA.quantity)
			where TL.quantity>0 and TL.transaction='$idTraslado'
			order by I.itemid asc;";
			$rs  = $this->_db->get_Connection()->Execute($sql);
			$contador = $rs->RecordCount();
			if (intval($contador) > 0) {
				while (!$rs->EOF) {
					$datos[] = [
						"idtransaccion"	=> $rs->fields[0],
						"iditem"	 	=> $rs->fields[1],
						"iddetalle"	 	=> $rs->fields[2],
						"codarticulo"	=> $rs->fields[3],
						"nomarticulo"	=> utf8_encode($rs->fields[4]),
						"nomarticulo2"	=> utf8_encode($rs->fields[5]),
						"unidad"	 	=> $rs->fields[6],
						"cantdisponible"=> $rs->fields[7],
						"canttrasladar"	=> $rs->fields[8],
						"numserielote"	=> $rs->fields[9],
						"desdedeposito"	=> $rs->fields[10],
						"paradeposito"	=> $rs->fields[11],
						"deestado"	 	=> $rs->fields[12],
						"aestado"	 	=> $rs->fields[13],
						"fechacreacion" => date("d/m/Y",strtotime($rs->fields[14])),
						"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[15])),
						"cantidad"	 	=> $rs->fields[16],
						"cantGenerada" 	=> $rs->fields[17],
						"principActivo" => $rs->fields[18],
						"iddeposito" 	=> $rs->fields[19],
						"secuencia" 	=> $rs->fields[20],
					];
					$rs->MoveNext();
				}
			}
			return $datos;
	}
	
	/* getCabeceraOrdenTrabajo DEPENDE PARA ORDEN DE TRASLADO */
	
	//T.TranID AS NroTraslado,
	//T.trandisplayname AS NombreTraslado,
	//where T.abbrevtype='WORKORD' and T.ID IN ('$idtransaccion');";
	public function getCabeceraOrdenTrabajo($idtransaccion)
	{
	
			$sql = "select
			T.ID as idtransaccion,
			T.TranID as NroOpe, 
			T.trandate as FechaCreacion, 
			T.trandisplayname as NombreTraslado,
			T.memo as Nota,
			I.itemid as codProd,
			t.custbodyiqsassydescription as producto,
			I.displayname as producto1,
			T.custbody41 AS Linea,
			T.custbodybio_cam_fechacaducidad as FexExp,
			T.custbodybio_cam_lote as Lote,
			T.custbody126 as FecFab,
			TL.quantity as CantProd,
			U.abbreviation as unidad,
			E1.firstname || ' ' || E1.lastname as EmiNomApe,
			T.custbody71 as firmaemitido,
			E2.firstname || ' ' || E2.lastname as RevNomApe,
			T.custbody72 as firmarevisado,
			E3.firstname || ' ' || E3.lastname as AjuNomApe,
			T.custbody73 as firmaajustado,
			E4.firstname || ' ' || E4.lastname as VerNomApe,
			T.custbody74 as firmaverificado,
			CL.name as TipOT
			from Transaction T
			left join TransactionLine TL ON ( TL.Transaction = T.ID and TL.id=0 )
			left join (select id,itemid,description,consumptionunit,displayname from item) I on I.id=TL.item
			INNER JOIN unitsTypeUom U on (U.internalid=I.consumptionunit)
			left join CUSTOMLIST1025 CL on CL.id=T.custbody8
			left join (select ID,firstname,lastname from Employee)E1 on E1.id=T.custbody67
			left join (select ID,firstname,lastname from Employee)E2 on E2.id=T.custbody69
			left join (select ID,firstname,lastname from Employee)E3 on E3.id=T.custbody70
			left join (select ID,firstname,lastname from Employee)E4 on E4.id=T.custbody68
			where T.ID IN ('$idtransaccion');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	=> $rs->fields[0],
					"NroOpe"		=> $rs->fields[1],
					"FechaCreacion"	=> date("d/m/Y",strtotime($rs->fields[2])),
					"NombreTraslado"=> utf8_encode($rs->fields[3]),
					"Nota"			=> utf8_encode($rs->fields[4]),
					"codProd"		=> $rs->fields[5],
					"producto"		=> utf8_encode($rs->fields[6]),
					"producto1"		=> utf8_encode($rs->fields[7]),
					"Linea"			=> utf8_encode($rs->fields[8]),
					"FexExp"		=> date("d/m/Y",strtotime($rs->fields[9])),
					"Lote"			=> $rs->fields[10],
					"FecFab"		=> date("d/m/Y",strtotime($rs->fields[11])),
					"CantProd"		=> $rs->fields[12],
					"unidad"		=> $rs->fields[13],
					"EmiNomApe"		=> utf8_encode($rs->fields[14]),
					"firmaemitido"	=> $rs->fields[15],
					"RevNomApe"		=> utf8_encode($rs->fields[16]),
					"firmarevisado"	=> $rs->fields[17],
					"AjuNomApe"		=> utf8_encode($rs->fields[18]),
					"firmaajustado"	=> $rs->fields[19],
					"VerNomApe"		=> utf8_encode($rs->fields[20]),
					"firmaverificado"=> $rs->fields[21],
					"TipOT"			=> utf8_encode($rs->fields[22]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getCabeceraOrdenTrabajo_ID($idtransaccion)
	{
	
		$sql = "select
			T.ID as idtransaccion,
			T.TranID as NroOpe, 
			T.trandate as FechaCreacion, 
			T.trandisplayname as NombreTraslado,
			T.memo as Nota,
			I.itemid as codProd,
			t.custbodyiqsassydescription as producto,
			I.displayname as producto1,
			T.custbody41 AS Linea,
			T.custbodybio_cam_fechacaducidad as FexExp,
			T.custbodybio_cam_lote as Lote,
			T.custbody126 as FecFab,
			TL.quantity as CantProd,
			U.abbreviation as unidad,
			E1.firstname || ' ' || E1.lastname as EmiNomApe,
			T.custbody105 as firmaemitido,
			E2.firstname || ' ' || E2.lastname as RevNomApe,
			T.custbody106 as firmarevisado,
			E3.firstname || ' ' || E3.lastname as AprobNomApe,
			T.custbody107 as firmaaaprobado,
			E4.firstname || ' ' || E4.lastname as RecibNomApe,
			T.custbody108 as firmarecibido,
			CL.name as TipOT 
			from Transaction T
			left join TransactionLine TL ON ( TL.Transaction = T.ID and TL.id=0 )
			left join (select id,itemid,description,consumptionunit,displayname from item) I on I.id=TL.item
			INNER JOIN unitsTypeUom U on (U.internalid=I.consumptionunit)
			left join CUSTOMLIST1025 CL on CL.id=T.custbody8
			left join (select ID,firstname,lastname from Employee)E1 on E1.id=T.custbody67
			left join (select ID,firstname,lastname from Employee)E2 on E2.id=T.custbody69
			left join (select ID,firstname,lastname from Employee)E3 on E3.id=T.custbody103
			left join (select ID,firstname,lastname from Employee)E4 on E4.id=T.custbody104
			where T.ID IN ('$idtransaccion');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	=> $rs->fields[0],
					"NroOpe"		=> $rs->fields[1],
					"FechaCreacion"	=> date("d/m/Y",strtotime($rs->fields[2])),
					"NombreTraslado"=> utf8_encode($rs->fields[3]),
					"Nota"			=> utf8_encode($rs->fields[4]),
					"codProd"		=> $rs->fields[5],
					"producto"		=> utf8_encode($rs->fields[6]),
					"producto1"		=> utf8_encode($rs->fields[7]),
					"Linea"			=> utf8_encode($rs->fields[8]),
					"FexExp"		=> date("d/m/Y",strtotime($rs->fields[9])),
					"Lote"			=> $rs->fields[10],
					"FecFab"		=> date("d/m/Y",strtotime($rs->fields[11])),
					"CantProd"		=> $rs->fields[12],
					"unidad"		=> $rs->fields[13],
					"EmiNomApe"		=> utf8_encode($rs->fields[14]),
					"firmaemitido"	=> $rs->fields[15],
					"RevNomApe"		=> utf8_encode($rs->fields[16]),
					"firmarevisado"	=> $rs->fields[17],
					"AprobNomApe"	=> utf8_encode($rs->fields[18]),
					"firmaaaprobado"=> $rs->fields[19],
					"RecibNomApe"	=> utf8_encode($rs->fields[20]),
					"firmarecibido" => $rs->fields[21],
					"TipOT"			=> utf8_encode($rs->fields[22]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

	/* getDetalleOrdenTrabajo  DEPENDE PARA ORDEN DE TRASLADO */

	public function getDetalleOrdenTrabajo($idtransaccion,$where1,$where2)
	{

		$sql="select distinct
			I.ID , 
			I.fullname as codigo,
			I.displayname,
			(CASE 
			WHEN PT.abbreviation = 'GR' THEN (TL.quantity*1000*-1) 
			WHEN PT.abbreviation = 'UND' THEN round(TL.quantity*-1,0)
			ELSE (TL.quantity*-1) 
			END) as cantidad,
			PT.abbreviation,
			(CASE WHEN PT.custrecord184 = 'T' THEN 'T' ELSE 'F' END) as principActivo,
			TL.linesequencenumber as secuencia 
			from (SELECT * FROM TransactionLine $where1) TL
			left JOIN Item I ON ( I.ID = TL.Item ) 
			INNER JOIN (
				select I.itemid, bcr.custrecord184, TL.quantity*-1 as quantity, U.abbreviation
				FROM Transaction T
				INNER JOIN (SELECT * FROM TransactionLine where itemsource like 'STOCK%') TL ON ( TL.Transaction = T.ID )
				INNER JOIN Item I ON ( I.ID = TL.Item )
				INNER JOIN unitsTypeUom U ON ( U.internalid=I.saleunit )
				LEFT JOIN bomrevisioncomponent bcr on (bcr.bomrevision=T.billofmaterialsrevision and bcr.item=TL.Item)
				WHERE T.ID IN ('$idtransaccion')
				) PT on PT.itemid=I.itemid  
			where TL.transaction='$idtransaccion' $where2 
			order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID"			=> $rs->fields[0],
					"codigo"		=> $rs->fields[1],
					"articulo"		=> utf8_encode($rs->fields[2]),
					"cantidad"		=> $rs->fields[3],
					"und"			=> $rs->fields[4],
					"principActivo"	=> $rs->fields[5],
					"secuencia" 	=> $rs->fields[6],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDetalleOrdenTrabajo_ID($idtransaccion)
	{

		$sql = "select 
            I.ID , 
			I.fullname as codigo,
			TL.memo as articulo,	
			(CASE WHEN PT.abbreviation = 'GR' THEN (TL.quantity*1000*-1) ELSE (TL.quantity*-1) END) as cantidad,
			PT.abbreviation,
			(CASE WHEN PT.custrecord184 is null THEN 'T' ELSE 'F' END) as principActivo,
			TL.linesequencenumber as secuencia 
			from TransactionLine TL
			left JOIN Item I ON ( I.ID = TL.Item ) 
                        INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
                        inner join  (
                                select I.itemid, bcr.custrecord184, TL.quantity*-1 as quantity, U.abbreviation
				FROM Transaction T
				INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
				LEFT JOIN Item I ON ( I.ID = TL.Item )
				INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
				LEFT JOIN bomrevisioncomponent bcr on (bcr.bomrevision=T.billofmaterialsrevision and bcr.item=TL.Item)
				WHERE TL.linesequencenumber>0 and T.ID in ('$idtransaccion') /*and trim(TL.itemType) like 'InvtPart%'*/
                        ) PT on PT.itemid=I.itemid
			where TL.transaction='$idtransaccion' and (I.fullname LIKE 'M%' or I.fullname LIKE 'B%' or I.fullname LIKE 'L%') and (TL.memo is not null )
			order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"ID"			=> $rs->fields[0],
					"codigo"		=> $rs->fields[1],
					"articulo"		=> utf8_encode($rs->fields[2]),
					"cantidad"		=> $rs->fields[3],
					"und"			=> $rs->fields[4],
					"principActivo"	=> $rs->fields[5],
					"secuencia" 	=> $rs->fields[6],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function cargarArticulos($idArt)
	{
		$sql="SELECT distinct TL.item as iditem, I.itemid || ' ' || I.description as articulo 
            FROM TransactionLine TL
            INNER JOIN Transaction T ON (  T.ID = TL.Transaction )
            INNER JOIN Item I ON ( I.ID = TL.Item )
            where TL.transaction='$idArt';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"iditem"	=> $rs->fields[0],
					"articulo"	=> utf8_encode($rs->fields[1]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function cargarArticulos_AJUSTE($idArt)
	{
		$sql="SELECT  
			TL.item as iditem,
			(CASE 
			WHEN TL.memo is not null 
			THEN I.itemid || ' ' || I.description || ' -  ' || L.fullname || ' -  ' || TL.memo 
			ELSE  I.itemid || ' ' || I.description || ' -  ' || L.fullname 
			END) AS articulo 
            FROM TransactionLine TL
            INNER JOIN Transaction T ON (  T.ID = TL.Transaction )
            INNER JOIN Item I ON ( I.ID = TL.Item )
            LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
            where TL.transaction='$idArt' 
            order by TL.linesequencenumber asc;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"iditem"	=> $rs->fields[0],
					"articulo"	=> utf8_encode($rs->fields[1]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	//WHERE T.abbrevtype='INV TRFR' AND T.TranID IN ('$idTraslado');";
	public function getIdTrasladoOP($idTraslado)
	{
		$sql="SELECT T.ID AS idtraslado,T.custbody42 AS idOP, T.memo as observacion
		FROM TRANSACTION T
		WHERE T.ID IN ('$idTraslado');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtraslado"=> $rs->fields[0],
					"idOP"		=> $rs->fields[1],
					"observacion"=> utf8_encode($rs->fields[2]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getIdTrasladoOP_AJUSTE($idTraslado)
	{
		$sql="SELECT T.ID AS idtraslado, T.memo as observacion
		FROM TRANSACTION T
		WHERE T.ID IN ('$idTraslado');";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtraslado"=> $rs->fields[0],
					"observacion"=> utf8_encode($rs->fields[1]),
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}

	public function getDatosEtiquetaSalida($idTraslado,$iddArt)
	{

		$sql = "SELECT distinct
            TL.transaction as idtransaccion,
            TL.item as iditem,
            U.internalid as iddetalle,
            TL.item as idarticulo,
            I.itemid as codarticulo,
            I.description as nomarticulo,
            I.displayname as nomarticulo2,
            I.totalquantityonhand as cantdisponible,
            TL.quantity as canttrasladar,
			PT.pluralabbreviation as unidad,
            TL.id as iddetalle1,
            INU.inventorynumber as numserielote,
            INU.expirationdate as fechacaducidad,
            (case when PT.pluralabbreviation='GRS' then IA.quantity*1000 else IA.quantity end) as cantidad
            FROM TransactionLine TL
            INNER JOIN Item I ON ( I.ID = TL.Item )
            INNER JOIN unitsTypeUom U on (U.internalid=I.purchaseunit)
            INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
            INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
            INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
            LEFT JOIN bin B ON (B.id=IA.bin)
            INNER JOIN (
                select TLX.transaction as P1 ,TLX.item as P2, IAX.quantity*-1 as P3, BX.binnumber as D1,ISTX.name as D2, IAX.id as id
                from TransactionLine TLX
                INNER JOIN Item IX ON ( IX.ID = TLX.Item )
                INNER JOIN unitsTypeUom UX on (UX.internalid=IX.purchaseunit)
                INNER JOIN inventoryAssignment IAX on (IAX.transaction=TLX.transaction and IAX.transactionline=TLX.id)
                INNER JOIN inventoryNumber INUX ON (INUX.id=IAX.inventorynumber)
                INNER JOIN inventoryStatus ISTX ON (ISTX.id=IAX.inventorystatus)
                LEFT JOIN bin BX ON (BX.id=IAX.bin)
                where TLX.quantity<0
                ) T1 on (P1=TL.transaction and P2=TL.item and P3=IA.quantity)
			INNER JOIN (
                select I.itemid, U.pluralabbreviation
                FROM Transaction T
                INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
                INNER JOIN Item I ON ( I.ID = TL.Item )
                INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
                ) PT on PT.itemid=I.itemid
            where TL.transaction='$idTraslado' and TL.item='$iddArt';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	=> $rs->fields[0],
					"iditem"		=> $rs->fields[1],
					"iddetalle"		=> $rs->fields[2],
					"idarticulo"	=> $rs->fields[3],
					"codarticulo"	=> $rs->fields[4],
					"nomarticulo"	=> utf8_encode($rs->fields[5]),
					"nomarticulo2"	=> utf8_encode($rs->fields[6]),
					"cantdisponible"=> $rs->fields[7],
					"canttrasladar"	=> $rs->fields[8],
					"unidad"		=> $rs->fields[9],
					"iddetalle1"	=> $rs->fields[10],
					"numserielote"	=> $rs->fields[11],
					"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[12])),
					"cantidad"		=> $rs->fields[13],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDatosEtiquetaSalida_AJUSTE($idTraslado,$iddArt)
	{

		$sql = "SELECT distinct
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
        TL.transaction as idtransaccion,
        TL.item as iditem,
        U.internalid as iddetalle,
        I.itemid as codarticulo,
        I.description as nomarticulo,
        I.displayname as nomarticulo2,
        I.totalquantityonhand as cantdisponible,
        TL.quantity,
	    U.pluralabbreviation as unidad,
	    U.abbreviation,
		T.custbody39,
		B.binnumber,
        TL.id as iddetalle1,
        INU.inventorynumber as numserielote,
        IST.name,
        INU.expirationdate as fechacaducidad,
	    TL.linelastmodifieddate as fechacreacion,
	    E.entitytitle,
		E.entityid,
		L.fullname,
		EP.firstname,
		EP.lastname,
        (case when U.abbreviation='GR' then IA.quantity*1000 else IA.quantity end) as cantidad
        FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
        INNER JOIN Item I ON ( I.ID = TL.Item )
        INNER JOIN unitsTypeUom U on (U.internalid=I.saleunit)
        INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
        INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
        INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
        LEFT JOIN bin B ON (B.id=IA.bin)
        LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
        where TL.transaction='$idTraslado' and TL.item='$iddArt';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"TranID"		=> $rs->fields[0],
					"createddate"	=> date("d/m/Y",strtotime($rs->fields[1])),
					"trandate"		=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"=> utf8_encode($rs->fields[3]),
					"memo"			=> utf8_encode($rs->fields[4]),
					"idtransaccion"	=> $rs->fields[5],
					"iditem"		=> $rs->fields[6],
					"iddetalle"		=> $rs->fields[7],
					"codarticulo"	=> $rs->fields[8],
					"nomarticulo"	=> utf8_encode($rs->fields[9]),
					"nomarticulo2"	=> utf8_encode($rs->fields[10]),
					"cantdisponible"=> $rs->fields[11],
					"quantity"		=> $rs->fields[12],
					"unidad"		=> $rs->fields[13],
					"abbreviation"	=> $rs->fields[14],
					"dua"			=> $rs->fields[15],
					"binnumber"		=> $rs->fields[16],
					"iddetalle1"	=> $rs->fields[17],
					"numserielote"	=> $rs->fields[18],
					"name"			=> utf8_encode($rs->fields[19]),
					"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[20])),
					"fechacreacion"	=> date("d/m/Y",strtotime($rs->fields[21])),
					"entitytitle"	=> utf8_encode($rs->fields[22]),
					"entityid"		=> utf8_encode($rs->fields[23]),
					"fullname"		=> utf8_encode($rs->fields[24]),
					"firstname"		=> utf8_encode($rs->fields[25]),
					"lastname"		=> utf8_encode($rs->fields[26]),
					"cantidad"		=> $rs->fields[27],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDatosEtiquetaSalida_AJUSTE_materiaprima($idTraslado,$iddArt)
	{
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		TL.custcol14,
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.custcol27,
		TL.linesequencenumber
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		LEFT JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$idTraslado') and I.id ='$iddArt'
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"entitytitle"			=> $rs->fields[5],
					"entityid"				=> $rs->fields[6],
					"total"					=> $rs->fields[7],
					"description"			=> utf8_encode($rs->fields[8]),
					"displayname"			=> utf8_encode($rs->fields[9]),
					"itemid"				=> $rs->fields[10],
					"purchasedescription"	=> utf8_encode($rs->fields[11]),
					"abbreviation"			=> $rs->fields[12],
					"dua"					=> $rs->fields[13],
					"binnumber"				=> $rs->fields[14],
					"inventorynumber"		=> $rs->fields[15],
					"name"					=> utf8_encode($rs->fields[16]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[17])),
					"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[18])),
					"quantity"				=> $rs->fields[19],
					"fullname"				=> utf8_encode($rs->fields[20]),
					"firstname"				=> utf8_encode($rs->fields[21]),
					"lastname"				=> utf8_encode($rs->fields[22]),
					"fecha_analisis"		=> date("d/m/Y",strtotime($rs->fields[23])),
					"linesequencenumber"	=> $rs->fields[24],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDatosEtiquetaSalida_AJUSTE_rechazo($idTraslado,$iddArt)
	{
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		TL.memo as memoControl,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		TL.custcol27,
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$idTraslado') and I.id ='$iddArt'
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"memoControl"			=> utf8_encode($rs->fields[5]),
					"entitytitle"			=> $rs->fields[6],
					"entityid"				=> $rs->fields[7],
					"total"					=> $rs->fields[8],
					"description"			=> utf8_encode($rs->fields[9]),
					"displayname"			=> utf8_encode($rs->fields[10]),
					"itemid"				=> $rs->fields[11],
					"purchasedescription"	=> utf8_encode($rs->fields[12]),
					"abbreviation"			=> $rs->fields[13],
					"dua"					=> $rs->fields[14],
					"binnumber"				=> $rs->fields[15],
					"inventorynumber"		=> $rs->fields[16],
					"name"					=> utf8_encode($rs->fields[17]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[18])),
					"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[19])),
					"quantity"				=> $rs->fields[20],
					"fullname"				=> utf8_encode($rs->fields[21]),
					"firstname"				=> utf8_encode($rs->fields[22]),
					"lastname"				=> utf8_encode($rs->fields[23]),
					"linesequencenumber"	=> $rs->fields[24],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function getDatosEtiquetaSalidaTodo($idTraslado)
	{

		$sql= "SELECT distinct
            TL.transaction as idtransaccion,
            TL.item as iditem,
            U.internalid as iddetalle,
            TL.item as idarticulo,
            I.itemid as codarticulo,
            I.description as nomarticulo,
            I.displayname as nomarticulo2,
            I.totalquantityonhand as cantdisponible,
            TL.quantity as canttrasladar,
			PT.pluralabbreviation as unidad,
            TL.id as iddetalle1,
            INU.inventorynumber as numserielote,
            INU.expirationdate as fechacaducidad,
            (case when PT.pluralabbreviation='GRS' then IA.quantity*1000 else IA.quantity end) as cantidad,
			T1.D1 as deposito 
            FROM TransactionLine TL
            INNER JOIN Item I ON ( I.ID = TL.Item )
            INNER JOIN unitsTypeUom U on (U.internalid=I.purchaseunit)
            INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
            INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
            INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
            LEFT JOIN bin B ON (B.id=IA.bin)
            INNER JOIN (
                select TLX.transaction as P1 ,TLX.item as P2, IAX.quantity*-1 as P3, BX.binnumber as D1,ISTX.name as D2, IAX.id as id
                from TransactionLine TLX
                INNER JOIN Item IX ON ( IX.ID = TLX.Item )
                INNER JOIN unitsTypeUom UX on (UX.internalid=IX.purchaseunit)
                INNER JOIN inventoryAssignment IAX on (IAX.transaction=TLX.transaction and IAX.transactionline=TLX.id)
                INNER JOIN inventoryNumber INUX ON (INUX.id=IAX.inventorynumber)
                INNER JOIN inventoryStatus ISTX ON (ISTX.id=IAX.inventorystatus)
                LEFT JOIN bin BX ON (BX.id=IAX.bin)
                where TLX.quantity<0
                ) T1 on (P1=TL.transaction and P2=TL.item and P3=IA.quantity)
			INNER JOIN (
                select I.itemid, U.pluralabbreviation
                FROM Transaction T
                INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
                INNER JOIN Item I ON ( I.ID = TL.Item )
                INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
                ) PT on PT.itemid=I.itemid
            where TL.transaction='$idTraslado';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"idtransaccion"	=> $rs->fields[0],
					"iditem"		=> $rs->fields[1],
					"iddetalle"		=> $rs->fields[2],
					"idarticulo"	=> $rs->fields[3],
					"codarticulo"	=> $rs->fields[4],
					"nomarticulo"	=> utf8_encode($rs->fields[5]),
					"nomarticulo2"	=> utf8_encode($rs->fields[6]),
					"cantdisponible"=> $rs->fields[7],
					"canttrasladar"	=> $rs->fields[8],
					"unidad"		=> $rs->fields[9],
					"iddetalle1"	=> $rs->fields[10],
					"numserielote"	=> $rs->fields[11],
					"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[12])),
					"cantidad"		=> $rs->fields[13],
					"deposito"		=> $rs->fields[14],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDatosEtiquetaSalidaTodo_AJUSTE($idTraslado)
	{

		$sql = "SELECT distinct
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
        TL.transaction as idtransaccion,
        TL.item as iditem,
        U.internalid as iddetalle,
        I.itemid as codarticulo,
        I.description as nomarticulo,
        I.displayname as nomarticulo2,
        I.totalquantityonhand as cantdisponible,
        TL.quantity,
	    U.pluralabbreviation as unidad,
	    U.abbreviation,
		T.custbody39,
		B.binnumber,
        TL.id as iddetalle1,
        INU.inventorynumber as numserielote,
        IST.name,
        INU.expirationdate as fechacaducidad,
	    TL.linelastmodifieddate as fechacreacion,
	    E.entitytitle,
		E.entityid,
		L.fullname,
		EP.firstname,
		EP.lastname,
        (case when U.abbreviation='GR' then IA.quantity*1000 else IA.quantity end) as cantidad
        FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
        INNER JOIN Item I ON ( I.ID = TL.Item )
        INNER JOIN unitsTypeUom U on (U.internalid=I.saleunit)
        INNER JOIN inventoryAssignment IA on (IA.transaction=TL.transaction and IA.transactionline=TL.id)
        INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
        INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
        LEFT JOIN bin B ON (B.id=IA.bin)
        LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
        where TL.transaction='$idTraslado';";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"TranID"		=> $rs->fields[0],
					"createddate"	=> date("d/m/Y",strtotime($rs->fields[1])),
					"trandate"		=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"=> utf8_encode($rs->fields[3]),
					"memo"			=> utf8_encode($rs->fields[4]),
					"idtransaccion"	=> $rs->fields[5],
					"iditem"		=> $rs->fields[6],
					"iddetalle"		=> $rs->fields[7],
					"codarticulo"	=> $rs->fields[8],
					"nomarticulo"	=> utf8_encode($rs->fields[9]),
					"nomarticulo2"	=> utf8_encode($rs->fields[10]),
					"cantdisponible"=> $rs->fields[11],
					"quantity"		=> $rs->fields[12],
					"unidad"		=> $rs->fields[13],
					"abbreviation"	=> $rs->fields[14],
					"dua"			=> $rs->fields[15],
					"binnumber"		=> $rs->fields[16],
					"iddetalle1"	=> $rs->fields[17],
					"numserielote"	=> $rs->fields[18],
					"name"			=> utf8_encode($rs->fields[19]),
					"fechacaducidad"=> date("d/m/Y",strtotime($rs->fields[20])),
					"fechacreacion"	=> date("d/m/Y",strtotime($rs->fields[21])),
					"entitytitle"	=> utf8_encode($rs->fields[22]),
					"entityid"		=> utf8_encode($rs->fields[23]),
					"fullname"		=> utf8_encode($rs->fields[24]),
					"firstname"		=> utf8_encode($rs->fields[25]),
					"lastname"		=> utf8_encode($rs->fields[26]),
					"cantidad"		=> $rs->fields[27],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	
	public function getDatosEtiquetaSalidaTodo_AJUSTE_materiaprima($idTraslado)
	{
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		TL.custcol14,
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.custcol27,
		TL.linesequencenumber
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		LEFT JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$idTraslado') 
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"entitytitle"			=> $rs->fields[5],
					"entityid"				=> $rs->fields[6],
					"total"					=> $rs->fields[7],
					"description"			=> utf8_encode($rs->fields[8]),
					"displayname"			=> utf8_encode($rs->fields[9]),
					"itemid"				=> $rs->fields[10],
					"purchasedescription"	=> utf8_encode($rs->fields[11]),
					"abbreviation"			=> $rs->fields[12],
					"dua"					=> $rs->fields[13],
					"binnumber"				=> $rs->fields[14],
					"inventorynumber"		=> $rs->fields[15],
					"name"					=> utf8_encode($rs->fields[16]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[17])),
					"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[18])),
					"quantity"				=> $rs->fields[19],
					"fullname"				=> utf8_encode($rs->fields[20]),
					"firstname"				=> utf8_encode($rs->fields[21]),
					"lastname"				=> utf8_encode($rs->fields[22]),
					"fecha_analisis"		=> date("d/m/Y",strtotime($rs->fields[23])),
					"linesequencenumber"	=> $rs->fields[24],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
	//TL.custcol14,
	public function getDatosEtiquetaSalidaTodo_AJUSTE_materialempaqueenvase($idTraslado)
	{
		$sql= "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		TL.memo,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.custcol27,
		TL.linesequencenumber
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		LEFT JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$idTraslado') 
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"entitytitle"			=> $rs->fields[5],
					"entityid"				=> $rs->fields[6],
					"total"					=> $rs->fields[7],
					"description"			=> utf8_encode($rs->fields[8]),
					"displayname"			=> utf8_encode($rs->fields[9]),
					"itemid"				=> $rs->fields[10],
					"purchasedescription"	=> utf8_encode($rs->fields[11]),
					"abbreviation"			=> $rs->fields[12],
					"dua"					=> $rs->fields[13],
					"binnumber"				=> $rs->fields[14],
					"inventorynumber"		=> $rs->fields[15],
					"name"					=> utf8_encode($rs->fields[16]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[17])),
					//"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[18])),
					"quantity"				=> $rs->fields[18],
					"fullname"				=> utf8_encode($rs->fields[19]),
					"firstname"				=> utf8_encode($rs->fields[20]),
					"lastname"				=> utf8_encode($rs->fields[21]),
					"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[22])),
					"linesequencenumber"	=> $rs->fields[23],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	public function getDatosEtiquetaSalidaTodo_AJUSTE_rechazo($idTraslado)
	{
		$sql = "SELECT 
		T.TranID, 
		T.createddate, 
		T.trandate, 
		T.trandisplayname,
		T.memo || ' - ' || TL.custcol8 as memo,
		TL.memo as memoControl,
		E.entitytitle,
		E.entityid,
		TL.quantity,
		I.description,
		I.displayname,
		I.itemid,
		I.purchasedescription,
		U.abbreviation,
		T.custbody39,
		B.binnumber,
		INU.inventorynumber,
		IST.name,
		INU.expirationdate,
		TL.custcol27,
		IA.quantity,
		L.fullname,
		EP.firstname,
		EP.lastname,
		TL.linesequencenumber
		FROM Transaction T
		INNER JOIN TransactionLine TL ON ( TL.Transaction = T.ID )
		INNER JOIN Item I ON ( I.ID = TL.Item )
		INNER JOIN unitsTypeUom U ON ( U.internalid=I.consumptionunit )
		INNER JOIN inventoryAssignment IA ON (IA.transaction=TL.Transaction and IA.transactionline=TL.id)
		LEFT JOIN bin B ON (B.id=IA.bin)
		INNER JOIN inventoryNumber INU ON (INU.id=IA.inventorynumber)
		INNER JOIN inventoryStatus IST ON (IST.id=IA.inventorystatus)
		LEFT JOIN entity E ON (E.id=T.entity)
		LEFT JOIN Location L ON (L.id=TL.inventoryreportinglocation)
		LEFT JOIN employee EP ON (EP.id=T.employee)
		WHERE T.ID IN ('$idTraslado') 
		order by TL.linesequencenumber;";
		$rs  = $this->_db->get_Connection()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$data[] = [
					"TranID"				=> $rs->fields[0],
					"createddate"			=> $rs->fields[1],
					"trandate"				=> date("d/m/Y",strtotime($rs->fields[2])),
					"trandisplayname"		=> utf8_encode($rs->fields[3]),
					"memo"					=> utf8_encode($rs->fields[4]),
					"memoControl"			=> utf8_encode($rs->fields[5]),
					"entitytitle"			=> $rs->fields[6],
					"entityid"				=> $rs->fields[7],
					"total"					=> $rs->fields[8],
					"description"			=> utf8_encode($rs->fields[9]),
					"displayname"			=> utf8_encode($rs->fields[10]),
					"itemid"				=> $rs->fields[11],
					"purchasedescription"	=> utf8_encode($rs->fields[12]),
					"abbreviation"			=> $rs->fields[13],
					"dua"					=> $rs->fields[14],
					"binnumber"				=> $rs->fields[15],
					"inventorynumber"		=> $rs->fields[16],
					"name"					=> utf8_encode($rs->fields[17]),
					"expirationdate"		=> date("d/m/Y",strtotime($rs->fields[18])),
					"fec_analisis"			=> date("d/m/Y",strtotime($rs->fields[19])),
					"quantity"				=> $rs->fields[20],
					"fullname"				=> utf8_encode($rs->fields[21]),
					"firstname"				=> utf8_encode($rs->fields[22]),
					"lastname"				=> utf8_encode($rs->fields[23]),
					"linesequencenumber"	=> $rs->fields[24],
				];
				$rs->MoveNext();
			}
		}
		return $data;
	}
	
	/* CONSULTAS MYSQL */
	
	public function queryMysql($id_ot)
	{
		$sql = "SELECT componente,cantidad FROM tb_registro_cantidad where id_ot='$id_ot'";
		$rs  = $this->_db->get_Connection1()->Execute($sql);
		$contador = $rs->RecordCount();
		if (intval($contador) > 0) {
			while (!$rs->EOF) {
				$datos[] = [
					"componente_mysql"	=> $rs->fields[0],
					"cantidad_mysql"	=> $rs->fields[1],
				];
				$rs->MoveNext();
			}
		}
		return $datos;
	}
}
