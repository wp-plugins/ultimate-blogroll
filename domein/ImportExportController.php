<?php
/**
 * Description of ImportExportController
 *
 * @author Jens
 */
require_once($path."domein/UltimateBlogrollController.php");
class ImportExportController extends UltimateBlogrollController {
    public function __construct() {
        parent::__construct();
    }

    public function execute() {
        global $gui;
        $gui["title"]       = __("Import/Export", "ultimate-blogroll");

        if(isset($_POST["ub_submit"]) && isset($_POST["ub_type"])) {
            if($_POST["ub_type"] == "import") {
                $this->GetBlogrollWordpress();
            } elseif($_POST["ub_type"] == "export") {
                $this->GetUltimateBlogrollLinks();
            }
        }

        ob_start(); // begin collecting output
        require_once(ABSPATH."wp-content/plugins/ultimate-blogroll/gui/ImportExport.php");
        $result = ob_get_clean();
        echo $result;
    }

    public function GetBlogrollWordpress() {
        $test = PersistentieMapper::Instance()->GetBlogrollWordpress();
        echo "<pre>";
        var_dump($test);
        echo "</pre>";
    }

    public function GetUltimateBlogrollLinks() {
        //PersistentieMapper::Instance()->;
    }
}
?>
