<?
	function file_force_download($file) {
		// echo "2";
		// echo $file;
	  if (file_exists($file)) {
	  	// echo "3";
	    // ���������� ����� ������ PHP, ����� �������� ������������ ������ ���������� ��� ������
	    // ���� ����� �� ������� ���� ����� �������� � ������ ���������!
	    if (ob_get_level()) {
	      ob_end_clean();
	    }
	    // ���������� ������� �������� ���� ���������� �����
	    header('Content-Description: File Transfer');
	    header('Content-Type: application/octet-stream');
	    header('Content-Disposition: attachment; filename=' . basename($file));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Cache-Control: must-revalidate');
	    header('Pragma: public');
	    header('Content-Length: ' . filesize($file));
	    // ������ ���� � ���������� ��� ������������
	    readfile($file);
	    // return false;
		//return 0;
	  }
	}
if(isset($_REQUEST["FILE"]) && !empty($_REQUEST["FILE"]))
{
	// echo "1";
	// $file = $_REQUEST["FILE"];
			//file_put_contents($_SERVER["DOCUMENT_ROOT"]."/test.txt", "2", FILE_APPEND);
	file_force_download($_SERVER["DOCUMENT_ROOT"]."/".$_REQUEST["FILE"]);
	
}
?>