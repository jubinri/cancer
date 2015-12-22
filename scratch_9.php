<?PHP
/*
	Title	: Define the scratch pattern
	Author	: Jubin Ri
	Created By : 2015.12.07
    Last Updated By : 2015.12.12 - first birthday of my daughter
    
*/

    function process( $obj, $contents )
    {
		global $img_url, $biology, $expertise, $certification, $education, $location, $research_interests, $phone, $email, $appointment, $zipcode, $speciality, $training, $honor, $rank, $dgree;


		// Get Other information
		$node_list = $obj->getElementsByTagName( 'img' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'width' ) == '150' )
			{
				$img_url = 'http://hospitals.jefferson.edu' . getAttribute( $node, 'src' );
			}
		}

		// Get Other information
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
            // Get Speciality
			if( getAttribute( $node, 'class' ) == 'specLineItem' )
			{
				$speciality .= $node->nodeValue . ', ';
			}
            
            // Appointment
			if( getAttribute( $node, 'class' ) == 'professional-titles' )
			{
				$appointment = $node->nodeValue;
			}

            
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl00_pnlThumbnail' )
			{
//				$img_url = getAttribute( $node->firstChild, 'src' );
			}
            
            // Get Location
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl02_pnlOffices' )
			{
				$location = $node->nodeValue;
				$location = str_replace( 'Office Locations', '', $location );
				$location = str_replace( 'View on Google Maps', '', $location );

			}
            
            // Get Certification
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabCertifications' )
			{
				$certification = $node->nodeValue;
				$certification = str_replace( 'Board Certifications', '', $certification );
			}
            
            // Get Education
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabEducation' )
			{
				$education = $node->nodeValue;
				$education = str_replace( 'Education', '', $education );
			}
            
            // Get Honor
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabAwards' )
			{
				$honor = $node->nodeValue;
				$honor = str_replace( 'Awards and Honors', '', $honor );
			}

            // Training
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabInternship' )
			{
				$training .= $node->nodeValue . ', ';
			}
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabResidency' )
			{
				$training .= $node->nodeValue . ', ';
			}
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabFellowship' )
			{
				$training .= $node->nodeValue . ', ';
			}
            
            // Expertise
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabConditions' )
			{
				$expertise .= 'Conditions : ' . substr( $node->nodeValue, strlen( 'Conditions') ) . ', ';
			}
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabTreatments' )
			{
				$expertise .= 'Treatments : ' . substr( $node->nodeValue, strlen( 'Treatments') ) . ', ';
			}
			if( getAttribute( $node, 'id' ) == 'main_0_contentpanel_1_ctl04_pnlTabTests' )
			{
				$expertise .= 'Tests : ' . substr( $node->nodeValue, strlen( 'Tests') ) . ', ';
			}
		}
		
		$pattern = '/Phone: *(\([0-9]+\) *[0-9\-]+)/';
		$matches = '';
		$cntPhone = preg_match_all( $pattern, $location, $matches );
		if( $cntPhone > 0 )
		{
			for( $i = 0; $i < $cntPhone; $i ++ ) $phone .= $matches[1][$i] . ', ';
		}
		else
		{
			$phone = ' 1-800-JEFF-NOW (800-533-3669)';
		}
        
        // Get the zip code
        $pattern = '/([0-9][0-9][0-9][0-9][0-9]([0-9\-]*))/';
        $matches = '';
        if( preg_match_all( $pattern , $location, $matches ) > 0 )
        {
            for( $i = 0; $i < count( $matches[1]); $i++ )
                $zipcode .= $matches[1][$i] . ', ';
        }        
        
        // Get the rank
        if( strpos( strtolower($appointment), 'associate professor') !== false )
            $rank = 2;
        elseif( strpos( strtolower($appointment), 'assistant professor') !== false )
            $rank = 3;
        elseif( strpos( strtolower($appointment), 'professor') !== false )
            $rank = 1;
        
        if( strlen( $training ) > 0 ) $training = substr( $training, 0, strlen( $training ) - 2 );
        if( strlen( $speciality ) > 0 ) $speciality = substr( $speciality, 0, strlen( $speciality ) - 2 );
        if( strlen( $expertise ) > 0 ) $expertise = substr( $expertise, 0, strlen( $expertise ) - 2 );
        if( strlen( $zipcode ) > 0 ) $zipcode = substr( $zipcode, 0, strlen( $zipcode ) - 2 );
    }
?>