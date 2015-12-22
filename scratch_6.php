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

		
		// Get Image
		$node_list = $obj->getElementsByTagName( 'img' );
		foreach( $node_list as $node )
		{
			if(  getAttribute( $node, 'class' ) == 'attachment-post-thumbnail wp-post-image' ){
				$img_url = getAttribute( $node, 'src' );
			}
		}
		
		// Get Appointment
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if(  getAttribute( $node, 'class' ) == 'desktop' ){
				$appointment = $node->nodeValue;
			}
		}
        
		// Get Other information
		$node_list = $obj->getElementsByTagName( 'h3' );
		foreach( $node_list as $node )
		{
			switch( $node->nodeValue )
			{
				case 'Areas of treatment:':
					$expertise = $node->nextSibling->nodeValue;
					break;
				case 'Specialty:':
					$speciality .= $node->nextSibling->nodeValue;
					break;
				case 'Specialties:':
					$speciality .= $node->nextSibling->nodeValue;
					break;
				case 'Practicing location:':
					$location = $node->nextSibling->nodeValue;
					break;
				case 'Education:':
					$education = $node->nextSibling->nodeValue;
					break;
				case 'Certifications:':
					$certification = $node->nextSibling->nodeValue;
					break;
				case 'Awards:':
					$honor = $node->nextSibling->nodeValue;
					break;
				case 'Internships:':
					$training .= 'Internships : ' . $node->nextSibling->nodeValue . ', ';
					break;
				case 'Residencies:':
					$training .= 'Residencies : ' . $node->nextSibling->nodeValue . ', ';
					break;
				case 'Fellowships:':
					$training .= 'Fellowships : ' . $node->nextSibling->nodeValue . ', ';
					break;
			}
		}

        // Get the rank
        if( strpos( strtolower($appointment), 'associate professor') !== false )
            $rank = 2;
        elseif( strpos( strtolower($appointment), 'assistant professor') !== false )
            $rank = 3;
        elseif( strpos( strtolower($appointment), 'professor') !== false )
            $rank = 1;
        
        if( strlen( $training ) > 0 ) $training = substr( $training, 0, strlen( $training ) - 2 );
		$phone = '(800) USC-CARE (800-872-2273)';
    }
?>