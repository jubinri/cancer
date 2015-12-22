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

		// Get image information
		$node_list = $obj->getElementsByTagName( 'img' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'id' ) == 'main_0_content_0_ProfileImage' )
			{
				$img_url = 'http://www.uhhospitals.org' . getAttribute( $node, 'src' );
			}
		}
		
		// Get the phone number
		$node_list = $obj->getElementsByTagName( 'h3' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'class' ) == 'phoneNumber' )
			{
				$phone = $node->nodeValue;
			}
		}

        // Get the Speciality
		$node_list = $obj->getElementsByTagName( 'h2' );
		foreach( $node_list as $node )
		{
            $speciality = $node->nodeValue;
		}
        
        // Get the appointment
		$node_list = $obj->getElementsByTagName( 'ul' );
		foreach( $node_list as $node )
		{
            if( getAttribute( $node, 'class') == 'dr-profile-titles' )
            {
//                echo 'here';
//                echo $obj->saveHTML( $node );
                $appointment = $node->nodeValue;
            }
		}
        
		// Get the other informations
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'class' ) == 'dr-profile-content' )
			{
				$info = trim($node->nodeValue);
                
                // biology
				$pos = strpos( $info, 'Video Library' );
                $posCertification = strpos( $info, 'Board Certifications' );
                if( $pos === false ) $pos = $posCertification;
                
                if( substr( $info, 0, 3 ) == 'Bio' )
                {
                    $biology = substr( $info, 3, $pos - 3 );
                }
				$info = substr( $info, $posCertification );
                
				// Certification
				$pos = strpos( $info, 'Education & Training' );
                
                if( $posCertification !== false )
                {
                    $certification = substr( $info, 0, $pos );
                    $certification = str_replace( 'Board Certifications', '', $certification );
                }
				$info = substr( $info, $pos );

				// Education & Training
				$pos = strpos( $info, 'Expertise' );
				$education = substr( $info, 0, $pos );
				$education = str_replace( 'Education & Training', '', $education );
				$info = substr( $info, $pos );

				// Expertise
				$pos = strpos( $info, 'Office Locations' );
				$expertise = substr( $info, 0, $pos );
				$expertise = str_replace( 'Expertise', '', $expertise );
				$info = substr( $info, $pos );

				// Location
				$pos = strpos( $info, 'var map_options' );
				$location = substr( $info, 0, $pos );
				$location = str_replace( 'Office Locations', '', $location );
				$location = str_replace( 'Get Directions', '   ', $location );
				$info = substr( $info, $pos );
			}
		}
        
        // Build the Education and Traning
        $posInternship = strpos( $education, 'Internship' );
        $posResidency = strpos( $education, 'Residency' );
        $posFellowship = strpos( $education, 'Fellowship' );
        
        $posTraining = 10000000 ;
        if( $posInternship !== false && $posTraining > $posInternship ) $posTraining = $posInternship;
        if( $posResidency !== false && $posTraining > $posResidency ) $posTraining = $posResidency;
        if( $posFellowship !== false && $posTraining > $posFellowship ) $posTraining = $posFellowship;
        
        if( $posTraining != 10000000 )
        {
            $training = substr( $education, $posTraining );
            $education =substr( $education, 0, $posTraining );
        }
        
        $education = str_replace( 'Medical / Professional School(s)', ' Medical / Professional School(s) : ', $education );
        $education = str_replace( 'Undergraduate', 'Undergraduate : ', $education );
        
        $training = str_replace( 'Internship', 'Internship : ', $training );
        $training = str_replace( 'Residency', ', Residency : ', $training );
        $training = str_replace( 'Fellowship', ', Fellowship : ', $training );
        if( substr( $training, 0, 2 ) == ', ' ) $training = substr( $training, 2 );

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
        elseif( strpos( strtolower($appointment), 'assistant professor') !== false || strpos( strtolower($appointment), 'assistant clinical professor') !== false )
            $rank = 3;
        elseif( strpos( strtolower($appointment), 'professor') !== false )
            $rank = 1;
        
        if( strlen( $zipcode ) > 0 ) $zipcode = substr( $zipcode, 0, strlen( $zipcode ) - 2 );    }
?>