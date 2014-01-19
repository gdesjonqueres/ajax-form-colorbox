<?php
require_once 'inc/define.inc.php';

session_start();

require_once 'inc/functions.inc.php';
require_once 'inc/data.inc.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" >
<html xmlns="http://www.w3.org/1999/xhtml" lang="fr">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Vehicules</title>
	<link rel="shortcut icon" href="../../favicon.ico" type="image/x-icon" />
	<!--<link href="../../dataneoDirect/css/datepicker.css" rel="stylesheet" type="text/css" />-->
	<link href="css/jquery-ui-1.10.1.custom.min.css" rel="stylesheet" type="text/css" />
	<link href="css/html.css" rel="stylesheet" type="text/css" />
	<link href="css/presentation.css" rel="stylesheet" type="text/css" />
	<link href="css/vehicules.css" rel="stylesheet" type="text/css" />
	<link href="css/chosen.gil-fork.css" rel="stylesheet" type="text/css" />
	<link href='http://fonts.googleapis.com/css?family=Voces' rel='stylesheet' type='text/css'>
</head>

<body id="vehicules">

	<div class="container">

		<form id="formVehic" class="gradient-grey">

			<div class="inline">

				<div class="control-group">
		        	<label class="control-label" for="marque">Make(s)</label>
		        	<div class="controls">
		        		<?=displaySelect('marque', $tabMarque, 'input-xlarge', false, 'multiple="multiple" data-placeholder="Select a make"')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="modele">Model(s)</label>
		        	<div class="controls">
		        		<select id="modele" name="marque" class="input-xlarge" multiple="multiple" data-placeholder="Select a model" disabled="disabled"></select>
		        	</div>
		  		</div>

		  		<div class="control-group" style="min-width: auto;">
		        	<label class="control-label" for="mec-min"><abbr title="Month of first registration">First Reg.</abbr></label>
		        	<div class="controls">
		        		<input type="text" id="mec-min" name="mec-min" class="date input-mini" placeholder="From" /> -
		        		<input type="text" id="mec-max" name="mec-max" class="date input-mini" placeholder="To" />
		        	</div>
		  		</div>

		  		<div class="control-group" style="min-width: auto;">
		        	<label class="control-label" for="etat">Status</label>
		        	<div class="controls">
		        		<?=displaySelect('etat', $tabEtat, 'input-small')?>
		        	</div>
		  		</div>

			</div><!--col-->

			<div class="inline collapse" style="margin-top: 15px; border-top: 1px dashed #aaa;">

				<div class="control-group">
		        	<label class="control-label" for="genre">Vehic. Type</label>
		        	<div class="controls">
		        		<?=displaySelect('genre', $tabGenre, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="segment">Range</label>
		        	<div class="controls">
		        		<?=displaySelect('segment', $tabSegment, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="energie">Fuel type</label>
		        	<div class="controls">
		        		<?=displaySelect('energie', $tabEnergie, 'input-medium')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="carrosserie">Body type</label>
		        	<div class="controls">
		        		<?=displaySelect('carrosserie', $tabCarrosserie, 'input-medium')?>
		        	</div>
		  		</div>

			</div><!--col-->

			<div class="inline collapse">

		  		<div class="control-group">
		        	<label class="control-label" for="ddi-min"><abbr title="Month of last registration">Last Reg.</abbr></label>
		        	<div class="controls">
		        		<input type="text" id="ddi-min" name="ddi-min" class="date input-mini" placeholder="From" /> -
		        		<input type="text" id="ddi-max" name="ddi-max" class="date input-mini" placeholder="To" />
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="pac-min">Purchase Price</label>
		        	<div class="controls">
		        		<?=displaySelect('pac-min', $tabPrix, 'input-small')?> -
		        		<?=displaySelect('pac-max', $tabPrix, 'input-small')?>
		        	</div>
		  		</div>

		  		<div class="control-group">
		        	<label class="control-label" for="parg-min">Market Price</label>
		        	<div class="controls">
		        		<?=displaySelect('parg-min', $tabPrix, 'input-small')?> -
		        		<?=displaySelect('parg-max', $tabPrix, 'input-small')?>
		        	</div>
		  		</div>

  			</div><!--col-->

	  		<div class="form-actions">
		    	<button type="button" id="btn-inclure" class="btn" title="Include those vehicules"><i class="icon-tick"></i> Select</button>
		    	<button type="button" id="btn-exclure" class="btn" title="Exclude those vehicules"><i class="icon-ban-circle"></i> Exclude</button>
		    	<button type="button" id="btn-show-options" class="btn" title="Show more selection criteria"><i class="icon-plus"></i> More criteria</button>
		    	<button type="button" id="btn-reset-form" class="btn btn-reset link" tabindex="-1">Clear off criteria</button>
		    </div>

		</form>

	</div><!--container-->

	<div class="container">

		<div class="panier gradient-green border-round shadow">
			<div class="panier-label border-round shadow" title="Vehicules included in your selection"><i class="icon-tick"></i></div>
			<div class="placeholder">Here are listed selected vehicules</div>
			<div class="loading"></div>
			<ul id="vehic-inclus"></ul>
		</div>

		<div class="panier gradient-grey border-round shadow" style="display: none;">
			<div class="panier-label border-round shadow" title="Vehicules excluded from your selection"><i class="icon-ban-circle"></i></div>
			<div class="loading"></div>
			<ul id="vehic-exclus"></ul>
		</div>

	</div><!--container-->

	<div style="text-align: left">
		<pre>
			<?php /*if (isset($_SESSION['_vehicules'])) {
				 print formatHtmlDump($_SESSION['_vehicules']);
			}*/?>
		</pre>
	</div>

	<script src="js/vendor/jquery.js" type="text/javascript"></script>
	<script src="js/vendor/jquery.mtz.monthpicker.js" type="text/javascript"></script>
	<script src="js/vendor/chosen.gil-fork.jquery.min.js" type="text/javascript"></script>
	<script src="js/base.js" type="text/javascript"></script>
	<script type="text/javascript">
	//<![CDATA[
		Dtno.config.urlAjax = '<?=URL_ROOT?>aaa/ajax/index.php';
	//]]>
	</script>
	<script src="js/vehicules.js" type="text/javascript"></script>
	<script type="text/javascript">
	//<![CDATA[
		jQuery(document).ready(function($) {

			$(".date").monthpicker({
				startYear: <?=(date('Y') - 25)?>,
				finalYear: <?=date('Y')?>,
				selectedYear: <?=(date('Y') - 3)?>,
				//monthNames: ['Jan', 'Fev', 'Mars', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct', 'Nov', 'Dec'],
				monthNames: ['Jan', 'Feb', 'March', 'Apr', 'May', 'June', 'July', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec'],
				pattern: 'mm/yyyy'
			});

			Dtno.vehicules.init();
		});
	//]]>
	</script>

</body><!--vehicules-->

</html>