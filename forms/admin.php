<?php
require_once( dirname(__FILE__).'/form.lib.php' );

define( 'PHPFMG_USER', "langus@mybce.catholic.edu.au" ); // must be a email address. for sending password to you.
define( 'PHPFMG_PW', "115da2" );

?>
<?php
/**
 * GNU Library or Lesser General Public License version 2.0 (LGPLv2)
*/

# main
# ------------------------------------------------------
error_reporting( E_ERROR ) ;
phpfmg_admin_main();
# ------------------------------------------------------




function phpfmg_admin_main(){
    $mod  = isset($_REQUEST['mod'])  ? $_REQUEST['mod']  : '';
    $func = isset($_REQUEST['func']) ? $_REQUEST['func'] : '';
    $function = "phpfmg_{$mod}_{$func}";
    if( !function_exists($function) ){
        phpfmg_admin_default();
        exit;
    };

    // no login required modules
    $public_modules   = false !== strpos('|captcha|', "|{$mod}|", "|ajax|");
    $public_functions = false !== strpos('|phpfmg_ajax_submit||phpfmg_mail_request_password||phpfmg_filman_download||phpfmg_image_processing||phpfmg_dd_lookup|', "|{$function}|") ;   
    if( $public_modules || $public_functions ) { 
        $function();
        exit;
    };
    
    return phpfmg_user_isLogin() ? $function() : phpfmg_admin_default();
}

function phpfmg_ajax_submit(){
    $phpfmg_send = phpfmg_sendmail( $GLOBALS['form_mail'] );
    $isHideForm  = isset($phpfmg_send['isHideForm']) ? $phpfmg_send['isHideForm'] : false;

    $response = array(
        'ok' => $isHideForm,
        'error_fields' => isset($phpfmg_send['error']) ? $phpfmg_send['error']['fields'] : '',
        'OneEntry' => isset($GLOBALS['OneEntry']) ? $GLOBALS['OneEntry'] : '',
    );
    
    @header("Content-Type:text/html; charset=$charset");
    echo "<html><body><script>
    var response = " . json_encode( $response ) . ";
    try{
        parent.fmgHandler.onResponse( response );
    }catch(E){};
    \n\n";
    echo "\n\n</script></body></html>";

}


function phpfmg_admin_default(){
    if( phpfmg_user_login() ){
        phpfmg_admin_panel();
    };
}



function phpfmg_admin_panel()
{    
    phpfmg_admin_header();
    phpfmg_writable_check();
?>    
<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td valign=top style="padding-left:280px;">

<style type="text/css">
    .fmg_title{
        font-size: 16px;
        font-weight: bold;
        padding: 10px;
    }
    
    .fmg_sep{
        width:32px;
    }
    
    .fmg_text{
        line-height: 150%;
        vertical-align: top;
        padding-left:28px;
    }

</style>

<script type="text/javascript">
    function deleteAll(n){
        if( confirm("Are you sure you want to delete?" ) ){
            location.href = "admin.php?mod=log&func=delete&file=" + n ;
        };
        return false ;
    }
</script>


<div class="fmg_title">
    1. Email Traffics
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=1">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=1">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_EMAILS_LOGFILE) ){
            echo '<a href="#" onclick="return deleteAll(1);">delete all</a>';
        };
    ?>
</div>


<div class="fmg_title">
    2. Form Data
</div>
<div class="fmg_text">
    <a href="admin.php?mod=log&func=view&file=2">view</a> &nbsp;&nbsp;
    <a href="admin.php?mod=log&func=download&file=2">download</a> &nbsp;&nbsp;
    <?php 
        if( file_exists(PHPFMG_SAVE_FILE) ){
            echo '<a href="#" onclick="return deleteAll(2);">delete all</a>';
        };
    ?>
</div>

<div class="fmg_title">
    3. Form Generator
</div>
<div class="fmg_text">
    <a href="http://www.formmail-maker.com/generator.php" onclick="document.frmFormMail.submit(); return false;" title="<?php echo htmlspecialchars(PHPFMG_SUBJECT);?>">Edit Form</a> &nbsp;&nbsp;
    <a href="http://www.formmail-maker.com/generator.php" >New Form</a>
</div>
    <form name="frmFormMail" action='http://www.formmail-maker.com/generator.php' method='post' enctype='multipart/form-data'>
    <input type="hidden" name="uuid" value="<?php echo PHPFMG_ID; ?>">
    <input type="hidden" name="external_ini" value="<?php echo function_exists('phpfmg_formini') ?  phpfmg_formini() : ""; ?>">
    </form>

		</td>
	</tr>
</table>

<?php
    phpfmg_admin_footer();
}



function phpfmg_admin_header( $title = '' ){
    header( "Content-Type: text/html; charset=" . PHPFMG_CHARSET );
?>
<html>
<head>
    <title><?php echo '' == $title ? '' : $title . ' | ' ; ?>PHP FormMail Admin Panel </title>
    <meta name="keywords" content="PHP FormMail Generator, PHP HTML form, send html email with attachment, PHP web form,  Free Form, Form Builder, Form Creator, phpFormMailGen, Customized Web Forms, phpFormMailGenerator,formmail.php, formmail.pl, formMail Generator, ASP Formmail, ASP form, PHP Form, Generator, phpFormGen, phpFormGenerator, anti-spam, web hosting">
    <meta name="description" content="PHP formMail Generator - A tool to ceate ready-to-use web forms in a flash. Validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. ">
    <meta name="generator" content="PHP Mail Form Generator, phpfmg.sourceforge.net">

    <style type='text/css'>
    body, td, label, div, span{
        font-family : Verdana, Arial, Helvetica, sans-serif;
        font-size : 12px;
    }
    </style>
</head>
<body  marginheight="0" marginwidth="0" leftmargin="0" topmargin="0">

<table cellspacing=0 cellpadding=0 border=0 width="100%">
    <td nowrap align=center style="background-color:#024e7b;padding:10px;font-size:18px;color:#ffffff;font-weight:bold;width:250px;" >
        Form Admin Panel
    </td>
    <td style="padding-left:30px;background-color:#86BC1B;width:100%;font-weight:bold;" >
        &nbsp;
<?php
    if( phpfmg_user_isLogin() ){
        echo '<a href="admin.php" style="color:#ffffff;">Main Menu</a> &nbsp;&nbsp;' ;
        echo '<a href="admin.php?mod=user&func=logout" style="color:#ffffff;">Logout</a>' ;
    }; 
?>
    </td>
</table>

<div style="padding-top:28px;">

<?php
    
}


function phpfmg_admin_footer(){
?>

</div>

<div style="color:#cccccc;text-decoration:none;padding:18px;font-weight:bold;">
	:: <a href="http://phpfmg.sourceforge.net" target="_blank" title="Free Mailform Maker: Create read-to-use Web Forms in a flash. Including validating form with CAPTCHA security image, send html email with attachments, send auto response email copy, log email traffics, save and download form data in Excel. " style="color:#cccccc;font-weight:bold;text-decoration:none;">PHP FormMail Generator</a> ::
</div>

</body>
</html>
<?php
}


function phpfmg_image_processing(){
    $img = new phpfmgImage();
    $img->out_processing_gif();
}


# phpfmg module : captcha
# ------------------------------------------------------
function phpfmg_captcha_get(){
    $img = new phpfmgImage();
    $img->out();
    //$_SESSION[PHPFMG_ID.'fmgCaptchCode'] = $img->text ;
    $_SESSION[ phpfmg_captcha_name() ] = $img->text ;
}



function phpfmg_captcha_generate_images(){
    for( $i = 0; $i < 50; $i ++ ){
        $file = "$i.png";
        $img = new phpfmgImage();
        $img->out($file);
        $data = base64_encode( file_get_contents($file) );
        echo "'{$img->text}' => '{$data}',\n" ;
        unlink( $file );
    };
}


function phpfmg_dd_lookup(){
    $paraOk = ( isset($_REQUEST['n']) && isset($_REQUEST['lookup']) && isset($_REQUEST['field_name']) );
    if( !$paraOk )
        return;
        
    $base64 = phpfmg_dependent_dropdown_data();
    $data = @unserialize( base64_decode($base64) );
    if( !is_array($data) ){
        return ;
    };
    
    
    foreach( $data as $field ){
        if( $field['name'] == $_REQUEST['field_name'] ){
            $nColumn = intval($_REQUEST['n']);
            $lookup  = $_REQUEST['lookup']; // $lookup is an array
            $dd      = new DependantDropdown(); 
            echo $dd->lookupFieldColumn( $field, $nColumn, $lookup );
            return;
        };
    };
    
    return;
}


function phpfmg_filman_download(){
    if( !isset($_REQUEST['filelink']) )
        return ;
        
    $info =  @unserialize(base64_decode($_REQUEST['filelink']));
    if( !isset($info['recordID']) ){
        return ;
    };
    
    $file = PHPFMG_SAVE_ATTACHMENTS_DIR . $info['recordID'] . '-' . $info['filename'];
    phpfmg_util_download( $file, $info['filename'] );
}


class phpfmgDataManager
{
    var $dataFile = '';
    var $columns = '';
    var $records = '';
    
    function phpfmgDataManager(){
        $this->dataFile = PHPFMG_SAVE_FILE; 
    }
    
    function parseFile(){
        $fp = @fopen($this->dataFile, 'rb');
        if( !$fp ) return false;
        
        $i = 0 ;
        $phpExitLine = 1; // first line is php code
        $colsLine = 2 ; // second line is column headers
        $this->columns = array();
        $this->records = array();
        $sep = chr(0x09);
        while( !feof($fp) ) { 
            $line = fgets($fp);
            $line = trim($line);
            if( empty($line) ) continue;
            $line = $this->line2display($line);
            $i ++ ;
            switch( $i ){
                case $phpExitLine:
                    continue;
                    break;
                case $colsLine :
                    $this->columns = explode($sep,$line);
                    break;
                default:
                    $this->records[] = explode( $sep, phpfmg_data2record( $line, false ) );
            };
        }; 
        fclose ($fp);
    }
    
    function displayRecords(){
        $this->parseFile();
        echo "<table border=1 style='width=95%;border-collapse: collapse;border-color:#cccccc;' >";
        echo "<tr><td>&nbsp;</td><td><b>" . join( "</b></td><td>&nbsp;<b>", $this->columns ) . "</b></td></tr>\n";
        $i = 1;
        foreach( $this->records as $r ){
            echo "<tr><td align=right>{$i}&nbsp;</td><td>" . join( "</td><td>&nbsp;", $r ) . "</td></tr>\n";
            $i++;
        };
        echo "</table>\n";
    }
    
    function line2display( $line ){
        $line = str_replace( array('"' . chr(0x09) . '"', '""'),  array(chr(0x09),'"'),  $line );
        $line = substr( $line, 1, -1 ); // chop first " and last "
        return $line;
    }
    
}
# end of class



# ------------------------------------------------------
class phpfmgImage
{
    var $im = null;
    var $width = 73 ;
    var $height = 33 ;
    var $text = '' ; 
    var $line_distance = 8;
    var $text_len = 4 ;

    function phpfmgImage( $text = '', $len = 4 ){
        $this->text_len = $len ;
        $this->text = '' == $text ? $this->uniqid( $this->text_len ) : $text ;
        $this->text = strtoupper( substr( $this->text, 0, $this->text_len ) );
    }
    
    function create(){
        $this->im = imagecreate( $this->width, $this->height );
        $bgcolor   = imagecolorallocate($this->im, 255, 255, 255);
        $textcolor = imagecolorallocate($this->im, 0, 0, 0);
        $this->drawLines();
        imagestring($this->im, 5, 20, 9, $this->text, $textcolor);
    }
    
    function drawLines(){
        $linecolor = imagecolorallocate($this->im, 210, 210, 210);
    
        //vertical lines
        for($x = 0; $x < $this->width; $x += $this->line_distance) {
          imageline($this->im, $x, 0, $x, $this->height, $linecolor);
        };
    
        //horizontal lines
        for($y = 0; $y < $this->height; $y += $this->line_distance) {
          imageline($this->im, 0, $y, $this->width, $y, $linecolor);
        };
    }
    
    function out( $filename = '' ){
        if( function_exists('imageline') ){
            $this->create();
            if( '' == $filename ) header("Content-type: image/png");
            ( '' == $filename ) ? imagepng( $this->im ) : imagepng( $this->im, $filename );
            imagedestroy( $this->im ); 
        }else{
            $this->out_predefined_image(); 
        };
    }

    function uniqid( $len = 0 ){
        $md5 = md5( uniqid(rand()) );
        return $len > 0 ? substr($md5,0,$len) : $md5 ;
    }
    
    function out_predefined_image(){
        header("Content-type: image/png");
        $data = $this->getImage(); 
        echo base64_decode($data);
    }
    
    // Use predefined captcha random images if web server doens't have GD graphics library installed  
    function getImage(){
        $images = array(
			'50AB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QkMYAhimMIY6IIkFNDCGMIQyOgSgiLG2Mjo6OoggiQUGiDS6NgTC1IGdFDZt2srUVZGhWcjua0VRhxALDUQxL6CVtZW1AVVMZApjCCuaXtYAhgCgGIqbByr8qAixuA8AvIPL7QTDh7UAAAAASUVORK5CYII=',
			'F957' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkMZQ1hDHUNDkMQCGlhbWYG0CIqYSKMrNrGpIBrhvtCopUtTM7NWZiG5L6CBMdChIaCVAUUvQyNQbAqqGAvQjoAABjS3MDo6OqCKMYYwhDKiiA1U+FERYnEfANypzVCsWfTHAAAAAElFTkSuQmCC',
			'5B60' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGVqRxQIaRFoZHR2mOqCKNbo2OAQEIIkFBoi0sjYwOogguS9s2tSwpVNXZk1Ddl8rUJ2jI0wdTAxoXiCKWABYLADFDpEpmG5hDcB080CFHxUhFvcBAMx1zJvVq63jAAAAAElFTkSuQmCC',
			'BD1E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXklEQVR4nGNYhQEaGAYTpIn7QgNEQximMIYGIIkFTBFpZQhhdEBWF9Aq0uiILjZFpNFhClwM7KTQqGkrs6atDM1Cch+aOrh5RImB3IImBnIzY6gjipsHKvyoCLG4DwDIr8vxfFIupgAAAABJRU5ErkJggg==',
			'491A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nM2Quw2AMAxEzxLZIAOFDYLkLMEUpGAD4w1SwJR8KgcoQeDrnu6kJ2O53IA/5R0/IYZgrBi7EYwpGEbsc8uI0TAnPgeh4I2faim9zr0avyjUmd6RlLBvE1cuTT73IJvLhRFTamv21f+ey43fCsWQyw445EDnAAAAAElFTkSuQmCC',
			'04B8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YWllDGaY6IImxBjBMZW10CAhAEhOZwhDK2hDoIIIkFtDK6IqkDuykqKVAELpqahaS+wJaRVrRzQtoFQ11RTMPaEcruh1At2DoxebmgQo/KkIs7gMAFb3MLQyeXbsAAAAASUVORK5CYII=',
			'3A42' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7RAMYAhgaHaY6IIkFTGEMYWh1CAhAVtnK2sow1dFBBFlsikijQ6BDgwiS+1ZGTVuZmZm1KgrZfUB1ro0OjQ4o5omGuoYGtKK4plUEpGoKA4pbwGIBqG4GiTmGhgyC8KMixOI+AAnCzekr9jrCAAAAAElFTkSuQmCC',
			'1B0B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQximMIY6IImxOoi0MoQyOgQgiYk6iDQ6Ojo6iKDoFWllbQiEqQM7aWXW1LClqyJDs5Dch6YOJtboChRDMw+rHRhuCcF080CFHxUhFvcBALqAyLJx0633AAAAAElFTkSuQmCC',
			'D2D9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGaY6IIkFTGFtZW10CAhAFmsVaXRtCHQQQRFjQBYDOylq6aqlS1dFRYUhuQ+obgprQ8BUNL0BQLEGVDFGB6AYqh1AnehuCQ0QDXVFc/NAhR8VIRb3AQAZOs6EkXgoHgAAAABJRU5ErkJggg==',
			'4D01' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpI37poiGMExhaEURCxFpZQhlmIosxhgi0ujo6BCKLMY6RaTRtSEAphfspGnTpq1MXRW1FNl9AajqwDA0FFOMYQrYDnQxkFvQxMBuDg0YDOFHPYjFfQCodMy9d042yAAAAABJRU5ErkJggg==',
			'0FE9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7GB1EQ11DHaY6IImxBog0sDYwBAQgiYlMAYkxOoggiQW0ooiBnRS1dGrY0tBVUWFI7oOoY5iKqRdoLoYdDCh2YHMLyEZWNDcPVPhREWJxHwBk+MrKj17FsQAAAABJRU5ErkJggg==',
			'A402' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nM2QsRGAMAhFP0U2iPskhT2FNJkmFmyQjGDjlMZKNJZ6F3734OAd2LvKGCm/+FGAoqAGwxyjQsBsmC8QijF4w1hpdpmzN35pa7WnlsuP1WubW+0NkUnm1sFtH5RiKE92unSskCwD/O/DvPgdEvrMYBD/eygAAAAASUVORK5CYII=',
			'28C6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYQxhCHaY6IImJTGFtZXQICAhAEgtoFWl0bRB0EEDW3craytrA6IDivmkrw5auWpmahey+ALA6FPMYHUDmAUlktzRA7EAWE2nAdEtoKKabByr8qAixuA8Axn7LGeEQX3oAAAAASUVORK5CYII=',
			'C008' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7WEMYAhimMEx1QBITaWUMYQhlCAhAEgtoZG1ldHR0EEEWaxBpdG0IgKkDOylq1bSVqauipmYhuQ9NHZJYIKp5WOzA5hZsbh6o8KMixOI+AEiOzEcyfZSbAAAAAElFTkSuQmCC',
			'909B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhhCGUMdkMREpjCGMDo6OgQgiQW0srayNgQ6iKCIiTS6AsUCkNw3beq0lZmZkaFZSO5jdRVpdAgJRDGPAajXAc08AaAdjGhi2NyCzc0DFX5UhFjcBwA82cqQf8VYvgAAAABJRU5ErkJggg==',
			'D1B6' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYElEQVR4nGNYhQEaGAYTpIn7QgMYAlhDGaY6IIkFTGEMYG10CAhAFmtlDWBtCHQQQBED6m10dEB2X9RSIApdmZqF5D6oOjTzGMDmiRASm8KA4ZZQoIvR3TxQ4UdFiMV9AM8fy+1JFSqUAAAAAElFTkSuQmCC',
			'B6D9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGaY6IIkFTGFtZW10CAhAFmsVaWRtCHQQQVEn0oAkBnZSaNS0sKWroqLCkNwXMEW0lbUhYKoImnmuDQENWMTQ7MB0CzY3D1T4URFicR8AsWnOUShnK0EAAAAASUVORK5CYII=',
			'A293' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUIdkMRYA1hbGR0dHQKQxESmiDS6NgQ0iCCJBbQygMUCkNwXtXTV0pWZUUuzkNwHVDeFIQSuDgxDQxkCGDDMA7oGQ4y1Ad0tAa2ioQ5obh6o8KMixOI+ACPEzTPubI+ZAAAAAElFTkSuQmCC',
			'0FF0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXElEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA1qRxVgDRBpYGximOiCJiUwBiwUEIIkFtILEGB1EkNwXtXRq2NLQlVnTkNyHpg6nGDY7sLkFpAsohuLmgQo/KkIs7gMAnU7K4uvss90AAAAASUVORK5CYII=',
			'6768' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WANEQx1CGaY6IImJTGFodHR0CAhAEgtoYWh0bXB0EEEWa2BoZW1ggKkDOykyatW0pVNXTc1Ccl/IFIYAVnTzWhkdWBsCUc0DmoYuJjJFpIERTS9rAFAFmpsHKvyoCLG4DwDH4syRKraPegAAAABJRU5ErkJggg==',
			'02DE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB0YQ1hDGUMDkMRYA1hbWRsdHZDViUwRaXRtCEQRC2hlQBYDOylq6aqlS1dFhmYhuQ+obgorpt4AdDGRKYwO6GJAtzSgu4XRQTTUFc3NAxV+VIRY3AcAzhjKQOn63+8AAAAASUVORK5CYII=',
			'9A0A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nGNYhQEaGAYTpIn7WAMYAhimMLQii4lMYQxhCGWY6oAkFtDK2sro6BAQgCIm0ujaEOggguS+aVOnrUxdFZk1Dcl9rK4o6iCwVTQUKBYagiQmADTP0dERRZ3IFJFGh1BGFDHWAKDYFFSxgQo/KkIs7gMAn73LyWbXIi4AAAAASUVORK5CYII=',
			'262A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2Q0QmAQAiG9eE2sH1sA4OzHWqK68EN6naoKTuCwKMei/IHwQ+FD2G7VII/5RW/IBhBwTyjORi2vLBjYjSFJCL+2qj0jsn75dxv6zBm7yeNgeG5d6RME8+o0bukwqTeo1RcuGaqGIN2Ffvqfw/mxm8H3vfKJrdPpg8AAAAASUVORK5CYII=',
			'295F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUNDkMREprC2sjYwOiCrC2gVaXRFE2MAiU2Fi0HcNG3p0tTMzNAsZPcFMAY6NASi6AXqakQXY21gAdqBKibSwNrK6OiIIhYayhjCEIrmlgEKPypCLO4DAKIByTJLw6rCAAAAAElFTkSuQmCC',
			'E38E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAU0lEQVR4nGNYhQEaGAYTpIn7QkNYQxhCGUMDkMQCGkRaGR0dHRhQxBgaXRsC0cWQ1YGdFBq1KmxV6MrQLCT3oanDZx4WMUy3YHPzQIUfFSEW9wEAc3LK2EGtCEMAAAAASUVORK5CYII=',
			'0BBA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDGVqRxVgDRFpZGx2mOiCJiUwRaXRtCAgIQBILaAWpc3QQQXJf1NKpYUtDV2ZNQ3IfmjqYGNC8wNAQDDsCUdRB3IKqF+JmRhSxgQo/KkIs7gMAQhTMIF68troAAAAASUVORK5CYII=',
			'3F7F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAXUlEQVR4nGNYhQEaGAYTpIn7RANEQ11DA0NDkMQCpogAyUAHFJWtWMRA6hodYWJgJ62Mmhq2aunK0Cxk94HUTWHENC8AU4zRAVUM5BbWBlQx0QBMsYEKPypCLO4DAD17yW9VPOUEAAAAAElFTkSuQmCC',
			'5FA5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbUlEQVR4nGNYhQEaGAYTpIn7QkNEQx2mMIYGIIkFNIg0MIQyOjCgiTE6OqKIBQaINLA2BLo6ILkvbNrUsKWrIqOikN3XClIHNhUBQWKhqGIBYHWBDshiIlPAegOQ3ccKtjdgqsMgCD8qQizuAwAub8xsBz5wwAAAAABJRU5ErkJggg==',
			'6274' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAb0lEQVR4nM2QMQ6AIAxF26E3wPt0cf8DDHKaksgNuIILp5QR0FGjfdtLfvJSqpcz+hOv9AnYS4Chc65IJkPqHXaX1JAHZ5Q0aUHXt8V6NGLs+nyhBuuwzQQCBz84VlaaW0xsdIIlrJP76n8PctN3AgQnzjKNk7AdAAAAAElFTkSuQmCC',
			'665D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDHUMdkMREprC2sjYwOgQgiQW0iDSCxESQxRpEGlinwsXAToqMmha2NDMzaxqS+0KmiLYyNASi6m0VaXTAIuaKJgZyC6OjI4pbQG5mCGVEcfNAhR8VIRb3AQDkCss97cqFywAAAABJRU5ErkJggg==',
			'156B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB1EQxlCGUMdkMRYHUQaGB0dHQKQxESBYqwNjkASWa9ICCuQDEBy38qsqUuXTl0ZmoXkPkYHhkZXNPPAYg2B6OZhEWNtxXBLCGMIupsHKvyoCLG4DwB768iKDBs01QAAAABJRU5ErkJggg==',
			'1B56' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHaY6IImxOoi0sjYwBAQgiYk6iDS6AlULoOgFqpvK6IDsvpVZU8OWZmamZiG5D6SOoSEQxTygWKNDQ6CDCJqYK6ZYK6OjA6pbQkRDGEIZUNw8UOFHRYjFfQA33MkeK8tV9QAAAABJRU5ErkJggg==',
			'0B40' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7GB1EQxgaHVqRxVgDRFoZWh2mOiCJiUwRaQSKBAQgiQW0AtUFOjqIILkvaunUsJWZmVnTkNwHUsfaCFcHE2t0DQ1EEQPb0YhqB9gtjahuwebmgQo/KkIs7gMAD+PM8gqToNsAAAAASUVORK5CYII=',
			'1880' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGVqRxVgdWFsZHR2mOiCJiTqINLo2BAQEoOgFqXN0EEFy38qslWGrQldmTUNyH5o6qBjIvEAsYtjsQHNLCKabByr8qAixuA8AE6PJA349olEAAAAASUVORK5CYII=',
			'E894' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7QkMYQxhCGRoCkMQCGlhbGR0dGlHFRBpdGwJa0dWxNgRMCUByX2jUyrCVmVFRUUjuA6ljCAl0QDfPoSEwNARNzBFIYnELihg2Nw9U+FERYnEfALN/zw5ZTpFkAAAAAElFTkSuQmCC',
			'3FEE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUElEQVR4nGNYhQEaGAYTpIn7RANEQ11DHUMDkMQCpog0sDYwOqCobMUihqoO7KSVUVPDloauDM1Cdh+x5mERw+YW0QCgGJqbByr8qAixuA8AXmfJGE41aY0AAAAASUVORK5CYII=',
			'B684' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZElEQVR4nGNYhQEaGAYTpIn7QgMYQxhCGRoCkMQCprC2Mjo6NKKItYo0sgJJVHUiDUB1UwKQ3BcaNS1sVeiqqCgk9wVMEQWa5+iAbp5rQ2BoCIZYADa3oIhhc/NAhR8VIRb3AQCMQ879RCoLvgAAAABJRU5ErkJggg==',
			'B757' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7QgNEQ11DHUNDkMQCpjA0ugJpEWSxVixiUxhaWacCaST3hUatmrY0M2tlFpL7gOoCwCagmMfoAJZBEWNtYG0ICEARmyLSwOjo6IDqZqArQhlRxAYq/KgIsbgPANt3zSTNWSdgAAAAAElFTkSuQmCC',
			'C6F8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WEMYQ1hDA6Y6IImJtLK2sjYwBAQgiQU0ijSyNjA6iCCLNYg0IKkDOylq1bSwpaGrpmYhuS+gQRTTvAaRRld08xoxxbC5BezmBgYUNw9U+FERYnEfAOd5y/oNrvX7AAAAAElFTkSuQmCC',
			'48AD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpI37pjCGAHGoA7JYCGsrQyijQwCSGGOISKOjo6ODCJIY6xTWVtaGQJgY2EnTpq0MW7oqMmsakvsCUNWBYWioSKNrKKoYwxSgWAO6GERvAIoYYwhQDNXNAxV+1INY3AcANMXLxDbOmdMAAAAASUVORK5CYII=',
			'8525' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAd0lEQVR4nM2QMQ6AMAhFYeAGeB8c3Glil97AW+DQG9QjONhT2pGqoybyB5IXAi9AvZXBn/KJH+kQIWJUx7iw4TiKn9PMRhY61uZmsDCJ89vTttdjScn5cYFVcuvdvsbKlfEqisLdDcoooN6PFGeKuskP/vdiHvxO9xLLXftWk4gAAAAASUVORK5CYII=',
			'E9FC' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMYQ1hDA6YGIIkFNLC2sjYwBIigiIk0ujYwOrBgEUN2X2jU0qWpoSuzkN0X0MAYiKQOKsbQiCnGgsUOTLeA3dzAgOLmgQo/KkIs7gMA837L7gPKC3QAAAAASUVORK5CYII=',
			'0DEA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAY0lEQVR4nGNYhQEaGAYTpIn7GB1EQ1hDHVqRxVgDRFpZGximOiCJiUwRaXRtYAgIQBILaAWJMTqIILkvaum0lamhK7OmIbkPTR2yWGgIhh2o6iBuQRWDuNkRRWygwo+KEIv7ACz6yzjs3s6RAAAAAElFTkSuQmCC',
			'3F9F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7RANEQx1CGUNDkMQCpog0MDo6OqCobBVpYG0IRBWbgiIGdtLKqKlhKzMjQ7OQ3QdUxxASiGEeA7p5QDFGNDFsbhENAOoNZUTVO0DhR0WIxX0AuQ7JRSxKsSEAAAAASUVORK5CYII=',
			'9EB5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQ1lDGUMDkMREpog0sDY6OiCrC2gFijUEYoo1Oro6ILlv2tSpYUtDV0ZFIbmP1RWkzqFBBNlmsHkBKGICUDtEMNziEIDsPoibGaY6DILwoyLE4j4APqLLi4wwab8AAAAASUVORK5CYII=',
			'8794' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdElEQVR4nGNYhQEaGAYTpIn7WANEQx1CGRoCkMREpjA0Ojo6NCKLBbQyNLoCSTR1rawNAVMCkNy3NGrVtJWZUVFRSO4DqgtgCAl0QDWP0YGhITA0BEWMtYER6BJUO0QaGB0dUMRYA0QaGNDcPFDhR0WIxX0Al4LOBKJZuC4AAAAASUVORK5CYII=',
			'A239' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdklEQVR4nGNYhQEaGAYTpIn7GB0YQxhDGaY6IImxBrC2sjY6BAQgiYlMEWl0aAh0EEESC2hlaHRodISJgZ0UtXTV0lVTV0WFIbkPqG4KUOVUZL2hoQwBQJkGVPMYHYAkmh2sDehuCWgVDXVEc/NAhR8VIRb3AQBH9M1bAVr/WAAAAABJRU5ErkJggg==',
			'4AA8' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpI37pjAEAPFUB2SxEMYQhlCGgAAkMcYQ1lZGR0cHESQx1ikija4NATB1YCdNmzZtZeqqqKlZSO4LQFUHhqGhoqGuoYEo5jGA1WETQ9ULFUN180CFH/UgFvcBAD1hzafGt4exAAAAAElFTkSuQmCC',
			'5B87' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkNEQxhCGUNDkMQCGkRaGR0dGkRQxRpdQSSSWGAARF0AkvvCpk0NWxW6amUWsvtawepaUWxuBZs3BVksACIWgCwmMgWk19EBWYw1AOxmFLGBCj8qQizuAwB4e8wXhutXpgAAAABJRU5ErkJggg==',
			'B743' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7QgNEQx0aHUIdkMQCpjA0OrQ6OgQgi7UCxaY6NIigqmtlCHRoCEByX2jUqmkrM7OWZiG5D6gugLURrg5qHqMDa2gAqnmtrA1AW9DsAPIaUd0SGgASQ3XzQIUfFSEW9wEARK3PVAGtTfIAAAAASUVORK5CYII=',
			'7FFE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAUElEQVR4nGNYhQEaGAYTpIn7QkNFQ11DA0MDkEVbRRpYGxgdGAiJTUERg7gpamrY0tCVoVlI7mN0wNTL2oApJoJFLAC3GKqbByj8qAixuA8API7JDRKw22EAAAAASUVORK5CYII=',
			'226E' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WAMYQxhCGUMDkMREprC2Mjo6OiCrC2gVaXRtQBVjaGUAijHCxCBumrZq6dKpK0OzkN0XwDCFFc08oK4A1oZAFDFWoCi6mAhIFE1vaKhoqAOamwcq/KgIsbgPAHSCySvlTxswAAAAAElFTkSuQmCC',
			'6B68' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WANEQxhCGaY6IImJTBFpZXR0CAhAEgtoEWl0bXB0EEEWaxBpZW1ggKkDOykyamrY0qmrpmYhuS8EaB4runmtIPMCUc3DIobNLdjcPFDhR0WIxX0AIRLNAiBFp0sAAAAASUVORK5CYII=',
			'9005' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7WAMYAhimMIYGIImJTGEMYQhldEBWF9DK2sro6IgmJtLo2hDo6oDkvmlTp61MXRUZFYXkPlZXkLqABhFkm1sxxQSgdohguIUhANl9EDczTHUYBOFHRYjFfQAm+Mqrf5mF+gAAAABJRU5ErkJggg==',
			'B8DE' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QgMYQ1hDGUMDkMQCprC2sjY6OiCrC2gVaXRtCEQVA6lDiIGdFBq1MmzpqsjQLCT3oanDbR4uO9Dcgs3NAxV+VIRY3AcAktjMhq7svL0AAAAASUVORK5CYII=',
			'A699' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGaY6IImxBrC2Mjo6BAQgiYlMEWlkbQh0EEESC2gVaUASAzspaum0sJWZUVFhSO4LaBVtZQgJmIqsNzRUpNGhIaABzbxGx4YANDsw3RLQiunmgQo/KkIs7gMA1T3MUOv7gd4AAAAASUVORK5CYII=',
			'6F39' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7WANEQx1DGaY6IImJTBFpYG10CAhAEgtoEQGSgQ4iyGINQF6jI0wM7KTIqKlhq6auigpDcl/IFJA6h6koeltFYCagi6HYgc0trAEiDYxobh6o8KMixOI+AIC3zUvPmflXAAAAAElFTkSuQmCC',
			'8DA9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7WANEQximMEx1QBITmSLSyhDKEBCAJBbQKtLo6OjoIIKqrtG1IRAmBnbS0qhpK1NXRUWFIbkPoi5gqgiaea6hAQ0YYg0B6Ha0sjYEoLgF5GagGIqbByr8qAixuA8ADcjN+xFv9IAAAAAASUVORK5CYII=',
			'94CF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpIn7WAMYWhlCHUNDkMREpjBMZXQIdEBWFwBUxdogiCbG6MrawAgTAztp2tSlS5euWhmaheQ+VleRViR1ENgqGuqKJibQytCKbgfQLa3oboG6GdW8AQo/KkIs7gMAF3/IvRsfcz0AAAAASUVORK5CYII=',
			'6DDA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7WANEQ1hDGVqRxUSmiLSyNjpMdUASC2gRaXRtCAgIQBZrAIkFOogguS8yatrK1FWRWdOQ3BcyBUUdRG8rWCw0BFMMRR3ELY4oYhA3M6KIDVT4URFicR8ARkDNuHPwLLYAAAAASUVORK5CYII=',
			'0809' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7GB0YQximMEx1QBJjDWBtZQhlCAhAEhOZItLo6OjoIIIkFtDK2sraEAgTAzspaunKsKWroqLCkNwHURcwFVWvSKNrQ0CDCIYdDih2YHMLNjcPVPhREWJxHwAWTMtoUO2O4gAAAABJRU5ErkJggg==',
			'86D4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZUlEQVR4nGNYhQEaGAYTpIn7WAMYQ1hDGRoCkMREprC2sjY6NCKLBbSKNLICSVR1Ig1AsSkBSO5bGjUtbOmqqKgoJPeJTBFtZW0IdEA3z7UhMDQEQywAm1tQxLC5eaDCj4oQi/sAgTzO9Yvx/6IAAAAASUVORK5CYII=',
			'1F1F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7GB1EQx2mMIaGIImxOog0MIQwOiCrEwWKMaKJMYLUTYGLgZ20Mmtq2KppK0OzkNyHpo5iMdEQoFtCHVHEBir8qAixuA8AssPGLQUJiHIAAAAASUVORK5CYII=',
			'E41F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkMYWhmmMIaGIIkFNDBMZQhhdGBAFQtlxBBjdAXqhYmBnRQatXTpqmkrQ7OQ3BfQINKKpA4qJhrqgCHGgEUdphjIzYyhjihiAxV+VIRY3AcASKHJ3oEO8eYAAAAASUVORK5CYII=',
			'9BE5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHUMDkMREpoi0sjYwOiCrC2gVaXTFFAOpc3VAct+0qVPDloaujIpCch+rK0gd0Fxkm8HmoYoJQO1AFoO4hSEA2X0QNztMdRgE4UdFiMV9AHfeyuzFqOXSAAAAAElFTkSuQmCC',
			'E553' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaElEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDHUIdkMQCGkQaWBsYHQIwxBiAJIpYCOtUEI1wX2jU1KVLM7OWZiG5Dyjf6AAkUc2DiKGZ1+iKIcbayujoiOKW0BDGEIZQBhQ3D1T4URFicR8AicPOHxcaSP4AAAAASUVORK5CYII=',
			'C7C5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7WENEQx1CHUMDkMREWhkaHR0CHZDVBTQyNLo2CKKKNTC0sjYwujoguS9q1appS1etjIpCch9QXQAryFwUvYwOGGKNrA2sQDuQxURaRYAqAwKQ3ccaAlQR6jDVYRCEHxUhFvcBAKOby8WkRIDDAAAAAElFTkSuQmCC',
			'4AD5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAa0lEQVR4nGNYhQEaGAYTpI37pjAEsIYyhgYgi4UwhrA2OjogqwOKtLI2BKKIsU4RaXRtCHR1QHLftGnTVqauioyKQnJfAFhdQIMIkt7QUNFQdDEGiHkOGGKNDgEB6GKhDFMdBkP4UQ9icR8AdaXM/W0EihwAAAAASUVORK5CYII=',
			'B560' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGVqRxQKmiDQwOjpMdUAWaxVpYG1wCAhAVRfC2sDoIILkvtCoqUuXTl2ZNQ3JfQFTGBpdHR1h6qDmAcUaAtHERIBiAWh2sLaiuyU0gDEE3c0DFX5UhFjcBwAFrs27ptqGOgAAAABJRU5ErkJggg==',
			'194F' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaUlEQVR4nGNYhQEaGAYTpIn7GB0YQxgaHUNDkMRYHVhbGVodHZDViTqINDpMRRVjBIkFwsXATlqZtXRpZmZmaBaS+4B2BLo2outlaHQNDUQTY2l0wFAHdAuamGgI2M0oYgMVflSEWNwHAArwx/zU4JxCAAAAAElFTkSuQmCC',
			'8DEB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7WANEQ1hDHUMdkMREpoi0sjYwOgQgiQW0ijS6AsVEUNWBxQKQ3Lc0atrK1NCVoVlI7kNTh9M8HHZguAWbmwcq/KgIsbgPANB/y+c77xc1AAAAAElFTkSuQmCC',
			'B58A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QgNEQxlCGVqRxQKmiDQwOjpMdUAWaxVpYG0ICAhAVRfC6OjoIILkvtCoqUtXha7MmobkvoApDI2OCHVQ8xgaXRsCQ0NQ7QCJoaqbwtrKiKY3NIAxhCGUEUVsoMKPihCL+wAyt8zjugdRFQAAAABJRU5ErkJggg==',
			'FAFD' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkMZAlhDA0MdkMQCGhhDWBsYHQJQxFhbQWIiKGIija4IMbCTQqOmrUwNXZk1Dcl9aOqgYqKhmGLY1EHEAjDFUNw8UOFHRYjFfQBMuMyRk8Uc6wAAAABJRU5ErkJggg==',
			'F3DF' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAVklEQVR4nGNYhQEaGAYTpIn7QkNZQ1hDGUNDkMQCGkRaWRsdHRhQxBgaXRsC0cVaWRFiYCeFRq0KW7oqMjQLyX1o6vCZh0UMm1vAbkYRG6jwoyLE4j4A0TrL1+XZqvUAAAAASUVORK5CYII=',
			'E5C0' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQxlCHVqRxQIaRBoYHQKmOqCJsTYIBASgioWwAlWKILkvNGrq0qWrVmZNQ3IfUE+jK0IdHjERoBi6Hayt6G4JDWEMQXfzQIUfFSEW9wEAkyrNVk0JTtYAAAAASUVORK5CYII=',
			'FE48' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAW0lEQVR4nGNYhQEaGAYTpIn7QkNFQxkaHaY6IIkFNIg0MLQ6BASgi011dBBBFwuEqwM7KTRqatjKzKypWUjuA6ljbcQ0jzU0ENO8Rix2YOjFdPNAhR8VIRb3AQCi3c4yKsQr6gAAAABJRU5ErkJggg==',
			'3F9A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbklEQVR4nGNYhQEaGAYTpIn7RANEQx1CGVqRxQKmiDQwOjpMdUBW2SrSwNoQEBCALDYFJBboIILkvpVRU8NWZkZmTUN2H1AdQwhcHdw8hobA0BA0McYGVHUQtziiiIkGAHmhjKjmDVD4URFicR8AGuzLIY0bfxUAAAAASUVORK5CYII=',
			'F864' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAX0lEQVR4nGNYhQEaGAYTpIn7QkMZQxhCGRoCkMQCGlhbGR0dGlHFRBpdGxxa0dWxNjBMCUByX2jUyrClU1dFRSG5D6zO0dEB07zA0BAMsQBsbkETw3TzQIUfFSEW9wEA1HvPOWgWmHwAAAAASUVORK5CYII=',
			'1EA4' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQxmmMDQEIImxOog0MIQyNCKLiQLFGB0dWgNQ9Io0sDYETAlAct/KrKlhS1dFRUUhuQ+iLtABQ29oYGgIpnkNWOxAERMNEQ1FFxuo8KMixOI+ALtvy1Ka3wd7AAAAAElFTkSuQmCC',
			'7426' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7QkMZWhlCGaY6IIu2MkxldHQICEAVC2VtCHQQQBabwujKABRDcV/U0qWrVmamZiG5j9FBpJWhlRHFPNYG0VCHKUAZJDERkC0BqGJAPa2MDgwoekFirKEBqG4eoPCjIsTiPgD3AcqEaJ+EwgAAAABJRU5ErkJggg==',
			'63AA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7WANYQximMLQii4lMEWllCGWY6oAkFtDC0Ojo6BAQgCzWwNDK2hDoIILkvsioVWFLV0VmTUNyX8gUFHUQva0Mja6hgaEh6GJo6kBuQdcLcjO62ECFHxUhFvcBAIygzJIlHY+eAAAAAElFTkSuQmCC',
			'EE79' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYklEQVR4nGNYhQEaGAYTpIn7QkNEQ1lDA6Y6IIkFNIiAyIAADLFABxF0sUZHmBjYSaFRU8NWLV0VFYbkPrC6KQxTMfQGMDSgizE6MGDYwQpUiewWsJsbGFDcPFDhR0WIxX0AM1TMz5/KKjYAAAAASUVORK5CYII=',
			'F33B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAV0lEQVR4nGNYhQEaGAYTpIn7QkNZQxhDGUMdkMQCGkRaWRsdHQJQxBgaHRoCHURQxVoZEOrATgqNWhW2aurK0Cwk96Gpw2ceFjFsbsF080CFHxUhFvcBANgwzZULYWpXAAAAAElFTkSuQmCC',
			'644A' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WAMYWhkaHVqRxUSmMExlaHWY6oAkFtDCEMow1SEgAFmsgdGVIdDRQQTJfZFRS5euzMzMmobkvpApIq2sjXB1EL2toqGuoYGhIShiILegqgO6BUMM4mZUsYEKPypCLO4DAJOGzGZe4/a6AAAAAElFTkSuQmCC',
			'09CB' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7GB0YQxhCHUMdkMRYA1hbGR0CHQKQxESmiDS6Ngg6iCCJBbSCxBhh6sBOilq6dGnqqpWhWUjuC2hlDERSBxVjAOsVQbGDBcMObG7B5uaBCj8qQizuAwCySsr6s9F06gAAAABJRU5ErkJggg==',
			'2701' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nM2QsRGAIAxFPwUb6D5hgzRpnEaKbIBsQMOURquglnonr3tJ7t6Bfnsr/sQnfZFnoQL1birIJNi8Y0VOyay/Vmg8Jr6v9tr60oY+Bru9k0CBri4aIdHYYkDGPhFzBcI/+L8XeejbAUXny1+DfxE5AAAAAElFTkSuQmCC',
			'07F9' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7GB1EQ11DA6Y6IImxBjA0ujYwBAQgiYlMAYkxOoggiQW0MrSyIsTATopaumra0tBVUWFI7gOqC2BtYJiKqpfRgRVkLoodrA1AMRQ7WANEQGIobgHZCDIP2c0DFX5UhFjcBwCJhMq/kHTd3AAAAABJRU5ErkJggg==',
			'1F28' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGaY6IImxOog0MDo6BAQgiYkCxVgbAoEksl4QLwCmDuyklVlTw0BEFpL7wOpaGVDMA4tNYcQ0LwBTjNEBVa9oCNAtoQEobh6o8KMixOI+AAqIyNH9+ykoAAAAAElFTkSuQmCC',
			'FB26' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpIn7QkNFQxhCGaY6IIkFNIi0Mjo6BASgijW6NgQ6CKCpYwCKIbsvNGpq2KqVmalZSO4Dq2tlxDDPYQqjgwi6WACGWCujAwOaXtEQ1tAAFDcPVPhREWJxHwALcszmoMCvYwAAAABJRU5ErkJggg==',
			'A41C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcUlEQVR4nGNYhQEaGAYTpIn7GB0YWhmmMEwNQBJjDWCYyhDCECCCJCYyhSGUMYTRgQVJLKCV0ZVhCtAEJPdFLV26dNW0lVnI7gtoFWlFUgeGoaGioQ5oYgGtDGB1LBhiqG4BiTGGOqC4eaDCj4oQi/sA4HDKvwYhcRcAAAAASUVORK5CYII=',
			'AF29' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcElEQVR4nGNYhQEaGAYTpIn7GB1EQx1CGaY6IImxBog0MDo6BAQgiYlMEWlgbQh0EEESC2gF8eBiYCdFLZ0atmplVlQYkvvA6loZpiLrDQ0F8qYAzUU3L4ABww5GBwYUt4DEWEMDUNw8UOFHRYjFfQCVnsvyj4gm2wAAAABJRU5ErkJggg==',
			'A991' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGVqRxVgDWFsZHR2mIouJTBFpdG0ICEUWC2gFi8H0gp0UtXTp0szMqKXI7gtoZQx0CAlAsSM0lKHRoQFVLKCVpdERQwzsFjQxsJtDAwZB+FERYnEfAK+fzOovmWrGAAAAAElFTkSuQmCC',
			'5A5B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAdUlEQVR4nGNYhQEaGAYTpIn7QkMYAlhDHUMdkMQCGhhDWBsYHQJQxFhbQWIiSGKBASKNrlPh6sBOCps2bWVqZmZoFrL7WkUaHRoCUcxjaBUNBYkhmxcAVOeKJiYyRaTR0dERRS8r0F6HUEYUNw9U+FERYnEfAPmvzC3zHfwPAAAAAElFTkSuQmCC',
			'FCAA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAYUlEQVR4nGNYhQEaGAYTpIn7QkMZQxmmMLQiiwU0sDY6hDJMdUARE2lwdHQICEATY20IdBBBcl9o1LRVS1dFZk1Dch+aOoRYaGBoCJqYK4Y61kZMMcZQdPMGKvyoCLG4DwCXps4zz1ESdQAAAABJRU5ErkJggg==',
			'276C' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAcklEQVR4nGNYhQEaGAYTpIn7WANEQx1CGaYGIImJTGFodHR0CBBBEgtoZWh0bXB0YEHW3crQytrA6IDivmmrpi2dujILxX0BDAGsQAOR7WV0YHRgbQhEEWMFw0AUO0SAkBHNLaGhQB6amwcq/KgIsbgPABZqymNkdy8FAAAAAElFTkSuQmCC',
			'64BA' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAc0lEQVR4nGNYhQEaGAYTpIn7WAMYWllDGVqRxUSmMExlbXSY6oAkFtDCEMraEBAQgCzWwOjK2ujoIILkvsiopUuXhq7MmobkvpApIq1I6iB6W0VDXRsCQ0NQxIBuaQhEUQd0C4ZeiJsZUcQGKvyoCLG4DwBarcw6gUnDLAAAAABJRU5ErkJggg==',
			'086B' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZklEQVR4nGNYhQEaGAYTpIn7GB0YQxhCGUMdkMRYA1hbGR0dHQKQxESmiDS6Njg6iCCJBbSytrICTQhAcl/U0pVhS6euDM1Cch9YHZp5Aa0g8wJRzIPYgSqGzS3Y3DxQ4UdFiMV9AF9Dys5lZLHYAAAAAElFTkSuQmCC',
			'F6A5' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAaklEQVR4nGNYhQEaGAYTpIn7QkMZQximMIYGIIkFNLC2MoQyOjCgiIk0Mjo6oos1sDYEujoguS80alrY0lWRUVFI7gtoEG1lBatGNc81FItYQ6CDCJpbgHoDUN3HGAIUm+owCMKPihCL+wAkOc11JlcFoAAAAABJRU5ErkJggg==',
			'AA47' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAbElEQVR4nGNYhQEaGAYTpIn7GB0YAhgaHUNDkMRYAxhDGFodGkSQxESmsLYyTEUVC2gVaXQIdGgIQHJf1NJpKzMzs1ZmIbkPpM610aEV2d7QUNFQ19CAKQzo5jU6BGCKOToQEhuo8KMixOI+ACHTzheoMj+TAAAAAElFTkSuQmCC',
			'463D' => 'iVBORw0KGgoAAAANSUhEUgAAAEkAAAAhAgMAAADoum54AAAACVBMVEX///8AAADS0tIrj1xmAAAAZ0lEQVR4nGNYhQEaGAYTpI37pjCGMIYyhjogi4WwtrI2OjoEIIkxhog0MjQEOoggibFOAfKA6kSQ3Ddt2rSwVVNXZk1Dcl/AFNFWJHVgGBoq0uiAZh7DFGximG7B6uaBCj/qQSzuAwBeGMu39xmTMgAAAABJRU5ErkJggg=='        
        );
        $this->text = array_rand( $images );
        return $images[ $this->text ] ;    
    }
    
    function out_processing_gif(){
        $image = dirname(__FILE__) . '/processing.gif';
        $base64_image = "R0lGODlhFAAUALMIAPh2AP+TMsZiALlcAKNOAOp4ANVqAP+PFv///wAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh+QQFCgAIACwAAAAAFAAUAAAEUxDJSau9iBDMtebTMEjehgTBJYqkiaLWOlZvGs8WDO6UIPCHw8TnAwWDEuKPcxQml0Ynj2cwYACAS7VqwWItWyuiUJB4s2AxmWxGg9bl6YQtl0cAACH5BAUKAAgALAEAAQASABIAAAROEMkpx6A4W5upENUmEQT2feFIltMJYivbvhnZ3Z1h4FMQIDodz+cL7nDEn5CH8DGZhcLtcMBEoxkqlXKVIgAAibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkphaA4W5upMdUmDQP2feFIltMJYivbvhnZ3V1R4BNBIDodz+cL7nDEn5CH8DGZAMAtEMBEoxkqlXKVIg4HibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpjaE4W5tpKdUmCQL2feFIltMJYivbvhnZ3R0A4NMwIDodz+cL7nDEn5CH8DGZh8ONQMBEoxkqlXKVIgIBibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpS6E4W5spANUmGQb2feFIltMJYivbvhnZ3d1x4JMgIDodz+cL7nDEn5CH8DGZgcBtMMBEoxkqlXKVIggEibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpAaA4W5vpOdUmFQX2feFIltMJYivbvhnZ3V0Q4JNhIDodz+cL7nDEn5CH8DGZBMJNIMBEoxkqlXKVIgYDibbK9YLBYvLtHH5K0J0IACH5BAUKAAgALAEAAQASABIAAAROEMkpz6E4W5tpCNUmAQD2feFIltMJYivbvhnZ3R1B4FNRIDodz+cL7nDEn5CH8DGZg8HNYMBEoxkqlXKVIgQCibbK9YLBYvLtHH5K0J0IACH5BAkKAAgALAEAAQASABIAAAROEMkpQ6A4W5spIdUmHQf2feFIltMJYivbvhnZ3d0w4BMAIDodz+cL7nDEn5CH8DGZAsGtUMBEoxkqlXKVIgwGibbK9YLBYvLtHH5K0J0IADs=";
        $binary = is_file($image) ? join("",file($image)) : base64_decode($base64_image); 
        header("Cache-Control: post-check=0, pre-check=0, max-age=0, no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
        header("Content-type: image/gif");
        echo $binary;
    }

}
# end of class phpfmgImage
# ------------------------------------------------------
# end of module : captcha


# module user
# ------------------------------------------------------
function phpfmg_user_isLogin(){
    return ( isset($_SESSION['authenticated']) && true === $_SESSION['authenticated'] );
}


function phpfmg_user_logout(){
    session_destroy();
    header("Location: admin.php");
}

function phpfmg_user_login()
{
    if( phpfmg_user_isLogin() ){
        return true ;
    };
    
    $sErr = "" ;
    if( 'Y' == $_POST['formmail_submit'] ){
        if(
            defined( 'PHPFMG_USER' ) && strtolower(PHPFMG_USER) == strtolower($_POST['Username']) &&
            defined( 'PHPFMG_PW' )   && strtolower(PHPFMG_PW) == strtolower($_POST['Password']) 
        ){
             $_SESSION['authenticated'] = true ;
             return true ;
             
        }else{
            $sErr = 'Login failed. Please try again.';
        }
    };
    
    // show login form 
    phpfmg_admin_header();
?>
<form name="frmFormMail" action="" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:380px;height:260px;">
<fieldset style="padding:18px;" >
<table cellspacing='3' cellpadding='3' border='0' >
	<tr>
		<td class="form_field" valign='top' align='right'>Email :</td>
		<td class="form_text">
            <input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" class='text_box' >
		</td>
	</tr>

	<tr>
		<td class="form_field" valign='top' align='right'>Password :</td>
		<td class="form_text">
            <input type="password" name="Password"  value="" class='text_box'>
		</td>
	</tr>

	<tr><td colspan=3 align='center'>
        <input type='submit' value='Login'><br><br>
        <?php if( $sErr ) echo "<span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
        <a href="admin.php?mod=mail&func=request_password">I forgot my password</a>   
    </td></tr>
</table>
</fieldset>
</div>
<script type="text/javascript">
    document.frmFormMail.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();
}


function phpfmg_mail_request_password(){
    $sErr = '';
    if( $_POST['formmail_submit'] == 'Y' ){
        if( strtoupper(trim($_POST['Username'])) == strtoupper(trim(PHPFMG_USER)) ){
            phpfmg_mail_password();
            exit;
        }else{
            $sErr = "Failed to verify your email.";
        };
    };
    
    $n1 = strpos(PHPFMG_USER,'@');
    $n2 = strrpos(PHPFMG_USER,'.');
    $email = substr(PHPFMG_USER,0,1) . str_repeat('*',$n1-1) . 
            '@' . substr(PHPFMG_USER,$n1+1,1) . str_repeat('*',$n2-$n1-2) . 
            '.' . substr(PHPFMG_USER,$n2+1,1) . str_repeat('*',strlen(PHPFMG_USER)-$n2-2) ;


    phpfmg_admin_header("Request Password of Email Form Admin Panel");
?>
<form name="frmRequestPassword" action="admin.php?mod=mail&func=request_password" method='post' enctype='multipart/form-data'>
<input type='hidden' name='formmail_submit' value='Y'>
<br><br><br>

<center>
<div style="width:580px;height:260px;text-align:left;">
<fieldset style="padding:18px;" >
<legend>Request Password</legend>
Enter Email Address <b><?php echo strtoupper($email) ;?></b>:<br />
<input type="text" name="Username"  value="<?php echo $_POST['Username']; ?>" style="width:380px;">
<input type='submit' value='Verify'><br>
The password will be sent to this email address. 
<?php if( $sErr ) echo "<br /><br /><span style='color:red;font-weight:bold;'>{$sErr}</span><br><br>\n"; ?>
</fieldset>
</div>
<script type="text/javascript">
    document.frmRequestPassword.Username.focus();
</script>
</form>
<?php
    phpfmg_admin_footer();    
}


function phpfmg_mail_password(){
    phpfmg_admin_header();
    if( defined( 'PHPFMG_USER' ) && defined( 'PHPFMG_PW' ) ){
        $body = "Here is the password for your form admin panel:\n\nUsername: " . PHPFMG_USER . "\nPassword: " . PHPFMG_PW . "\n\n" ;
        if( 'html' == PHPFMG_MAIL_TYPE )
            $body = nl2br($body);
        mailAttachments( PHPFMG_USER, "Password for Your Form Admin Panel", $body, PHPFMG_USER, 'You', "You <" . PHPFMG_USER . ">" );
        echo "<center>Your password has been sent.<br><br><a href='admin.php'>Click here to login again</a></center>";
    };   
    phpfmg_admin_footer();
}


function phpfmg_writable_check(){
 
    if( is_writable( dirname(PHPFMG_SAVE_FILE) ) && is_writable( dirname(PHPFMG_EMAILS_LOGFILE) )  ){
        return ;
    };
?>
<style type="text/css">
    .fmg_warning{
        background-color: #F4F6E5;
        border: 1px dashed #ff0000;
        padding: 16px;
        color : black;
        margin: 10px;
        line-height: 180%;
        width:80%;
    }
    
    .fmg_warning_title{
        font-weight: bold;
    }

</style>
<br><br>
<div class="fmg_warning">
    <div class="fmg_warning_title">Your form data or email traffic log is NOT saving.</div>
    The form data (<?php echo PHPFMG_SAVE_FILE ?>) and email traffic log (<?php echo PHPFMG_EMAILS_LOGFILE?>) will be created automatically when the form is submitted. 
    However, the script doesn't have writable permission to create those files. In order to save your valuable information, please set the directory to writable.
     If you don't know how to do it, please ask for help from your web Administrator or Technical Support of your hosting company.   
</div>
<br><br>
<?php
}


function phpfmg_log_view(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    
    phpfmg_admin_header();
   
    $file = $files[$n];
    if( is_file($file) ){
        if( 1== $n ){
            echo "<pre>\n";
            echo join("",file($file) );
            echo "</pre>\n";
        }else{
            $man = new phpfmgDataManager();
            $man->displayRecords();
        };
     

    }else{
        echo "<b>No form data found.</b>";
    };
    phpfmg_admin_footer();
}


function phpfmg_log_download(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );

    $file = $files[$n];
    if( is_file($file) ){
        phpfmg_util_download( $file, PHPFMG_SAVE_FILE == $file ? 'form-data.csv' : 'email-traffics.txt', true, 1 ); // skip the first line
    }else{
        phpfmg_admin_header();
        echo "<b>No email traffic log found.</b>";
        phpfmg_admin_footer();
    };

}


function phpfmg_log_delete(){
    $n = isset($_REQUEST['file'])  ? $_REQUEST['file']  : '';
    $files = array(
        1 => PHPFMG_EMAILS_LOGFILE,
        2 => PHPFMG_SAVE_FILE,
    );
    phpfmg_admin_header();

    $file = $files[$n];
    if( is_file($file) ){
        echo unlink($file) ? "It has been deleted!" : "Failed to delete!" ;
    };
    phpfmg_admin_footer();
}


function phpfmg_util_download($file, $filename='', $toCSV = false, $skipN = 0 ){
    if (!is_file($file)) return false ;

    set_time_limit(0);


    $buffer = "";
    $i = 0 ;
    $fp = @fopen($file, 'rb');
    while( !feof($fp)) { 
        $i ++ ;
        $line = fgets($fp);
        if($i > $skipN){ // skip lines
            if( $toCSV ){ 
              $line = str_replace( chr(0x09), ',', $line );
              $buffer .= phpfmg_data2record( $line, false );
            }else{
                $buffer .= $line;
            };
        }; 
    }; 
    fclose ($fp);
  

    
    /*
        If the Content-Length is NOT THE SAME SIZE as the real conent output, Windows+IIS might be hung!!
    */
    $len = strlen($buffer);
    $filename = basename( '' == $filename ? $file : $filename );
    $file_extension = strtolower(substr(strrchr($filename,"."),1));

    switch( $file_extension ) {
        case "pdf": $ctype="application/pdf"; break;
        case "exe": $ctype="application/octet-stream"; break;
        case "zip": $ctype="application/zip"; break;
        case "doc": $ctype="application/msword"; break;
        case "xls": $ctype="application/vnd.ms-excel"; break;
        case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
        case "gif": $ctype="image/gif"; break;
        case "png": $ctype="image/png"; break;
        case "jpeg":
        case "jpg": $ctype="image/jpg"; break;
        case "mp3": $ctype="audio/mpeg"; break;
        case "wav": $ctype="audio/x-wav"; break;
        case "mpeg":
        case "mpg":
        case "mpe": $ctype="video/mpeg"; break;
        case "mov": $ctype="video/quicktime"; break;
        case "avi": $ctype="video/x-msvideo"; break;
        //The following are for extensions that shouldn't be downloaded (sensitive stuff, like php files)
        case "php":
        case "htm":
        case "html": 
                $ctype="text/plain"; break;
        default: 
            $ctype="application/x-download";
    }
                                            

    //Begin writing headers
    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: public"); 
    header("Content-Description: File Transfer");
    //Use the switch-generated Content-Type
    header("Content-Type: $ctype");
    //Force the download
    header("Content-Disposition: attachment; filename=".$filename.";" );
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".$len);
    
    while (@ob_end_clean()); // no output buffering !
    flush();
    echo $buffer ;
    
    return true;
 
    
}
?>