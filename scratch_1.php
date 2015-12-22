<?PHP
/*
	Title	: Define the scratch pattern
	Author	: Jubin Ri
	Created By : 2015.12.07
    Last Updated By : 2015.12.11
*/
    
    function process( $obj, $contents )
    {
		global $obj, $contents, $img_url, $biology, $expertise, $certification, $education, $location, $research_interests, $phone, $email, $appointment, $zipcode, $speciality, $training, $honor, $rank;

        // Get Image
		$pattern = '/<div id=\'physicianHeadshot\'>([^<]*)<img src="([^"]*)"([^>]*)>([^<]*)<\/div>/';
		$matches = '';
		preg_match_all( $pattern , $contents, $matches );

		$img_url = 'http://www.fccc.edu' . $matches[2][0];
		
		// Get Honor
		$pattern = '/<h4>Honors and Awards<\/h4>([^<]*)<p>([^<]*)<\/p>/';
		$matches = '';
		if( preg_match_all( $pattern , $contents, $matches ) > 0 )
		{
			$honor = $matches[2][0];
		}
        
		// Get Education
		$pattern = '/<h4>Medical Education<\/h4>([^<]*)<p>([^<]*)<\/p>/';
		$matches = '';
		if( preg_match_all( $pattern , $contents, $matches ) > 0 )
		{
			$education = $matches[2][0];
		}

		// Get Certification
		$pattern = '/<h4>Certifications:<\/h4>([^<]*)<p>([^<]*)<\/p>/';
		$matches = '';
		if( preg_match_all( $pattern , $contents, $matches ) > 0 )
		{
			$certification = $matches[2][0];
		}

		// Get Research Interests
		$pattern = '/<h4>Research Interests<\/h4>([^<]*)<p>([^<]*)<\/p>/';
		$matches = '';
		if( preg_match_all( $pattern , $contents, $matches ) > 0 )
		{
			$research_interests = $matches[2][0];
		}
		
		// Get Expertise
		$pattern = '/<h4>Clinical Expertise:<\/h4>([^<]*)<p>([^<]*)<\/p>/';
		$matches = '';
		if( preg_match_all( $pattern , $contents, $matches ) > 0 )
		{
			$expertise = $matches[2][0];
		}
        
		// Get the specility and biology
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'id' ) == 'physicianHeadshot' )
			{
				$speciality = $node->nextSibling->nextSibling->nodeValue;
			}
			if( getAttribute( $node, 'id' ) == 'physicianQuotebox' )  $biology = $node->nodeValue;
            
            // Get the location
			if( getAttribute( $node, 'id' ) == 'physicianAppt' )
            {
                $location = $node->nodeValue;
                $location = trim(str_replace( "New patients can request an appointment online or call", "", $location ));
                
                // The the phone and zipcode
                $pos = strpos( $location, " ");
                $phone = substr( $location, 0, $pos );
                
                $location = substr( $location, $pos );
                
                $pattern = '/([0-9][0-9][0-9][0-9][0-9]([0-9\-]*))/';
                $matches = '';
                if( preg_match_all( $pattern , $location, $matches ) > 0 )
                {
                    $zipcode = $matches[1][0];
                }
                
            }

		}
	       
        // Get $speciality again
        if( trim( $speciality ) == '' )
        {
            $node_list = $obj->getElementsByTagName( 'h2' );
            foreach( $node_list as $node )
            {
                $speciality = $node->nodeValue;
            }
            
        }
		$node_list = $obj->getElementsByTagName( 'h4' );
        $start = 0;
		foreach( $node_list as $node )
		{
            $start ++;
            if( $start == 1 )
            {
                $appointment = trim($node->nodeValue);
                if( $appointment == 'Clinical Expertise:') $appointment = '';
                if( substr( $appointment, 0, 5 ) == 'Video') $start = 0;
                if( substr( $appointment, 0, 4 ) == 'Why ') $start = 0;
                if( strpos( $appointment, "Patient Stories" ) !== false ) $start = 0;
                
            }
            
            
			$nodeValue = trim( $node->nodeValue );
			if( $nodeValue == 'Clinical Expertise:' ) $expertise = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Certifications:' && $certification == '' ) $certification = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Medical Education' && $education == '' ) $education = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Research Interests' && $research_interests == '' ) $research_interests = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Clinical Expertise:' && $expertise == '' ) $expertise = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Honors and Awards' && $honor == '' ) $honor = $node->nextSibling->nodeValue;
			if( $nodeValue == 'Residencies' ) $training .= 'Residencies : ' . $node->nextSibling->nodeValue;
			if( $nodeValue == 'Fellowships' ) $training .= 'Fellowships : ' . $node->nextSibling->nodeValue;
		}
    }
?>