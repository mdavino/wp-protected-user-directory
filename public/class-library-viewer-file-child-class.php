<?php

class Library_Viewer_File_Protected extends Library_Viewer_File{
    protected function Library_Viewer_File_Protected__rest_globals($rest_globals)
	{
		$a = parent::Library_Viewer_File__rest_globals($rest_globals);
        array_push($a, 'real_path');
        return $a;
	}

}