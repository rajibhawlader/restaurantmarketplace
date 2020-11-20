<?PHP //$Id: block_login.php,v 1.2 2004/08/22 16:54:45 defacer Exp $

// Change this url to the src path of your live help:
$livehelpurl = "http://www.lezionionline.net/livehelp/livehelp_js.php?relative=Y&amp;department=1&amp;pingtimes=60";


// Blocco per l'utilizzo di LiveHelp all'interno di un blocco Moodle
//Adattamento php a cura di Lezionionline.net (http://www.lezionionline.net)
//Informazioni sul suo utilizzo sul nostro sito nell'area corsi
class CourseBlock_LiveHelp extends MoodleBlock {
    function CourseBlock_LiveHelp ($course) {
        $this->title = "LiveHelp";
        $this->content_type = BLOCK_TYPE_TEXT;
        $this->course = $course;
        $this->version = 2004050300;
    }

    function get_content() {
        global $USER, $CFG;

        if ($this->content !== NULL) {
            return $this->content;
        }

        $this->content = New object;
        $this->content->footer = '';
		$this->content->text = "<script type=\"text/javascript\" src=\"".$livehelpurl."\"></script>"; /* Script di richiamo routine LiveHelp*/
		return $this->content;
    }

    function hide_header() {
        return false;
    }
}
?>