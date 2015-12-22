<?PHP
/*
	Title	: Define the scratch pattern
	Author	: Jubin Ri
	Created By : 2015.12.07
    Last Updated By : 2015.12.12 - first birthday of my daughter
    
*/

    function process( $obj, $contents )
    {
		global $img_url, $biology, $expertise, $certification, $education, $location, $research_interests, $phone, $email, $appointment, $zipcode, $speciality, $training, $honor, $rank, $dgree, $first_name, $last_name;
		
		// Get Image
		$node_list = $obj->getElementsByTagName( 'img' );
		foreach( $node_list as $node )
		{
			if(  getAttribute( $node, 'typeof' ) == 'foaf:Image' ){
				$img_url = getAttribute( $node, 'src' );
			}
		}
		
        // Get First Name, Last Name, Degree
		$node_list = $obj->getElementsByTagName( 'h1' );
		foreach( $node_list as $node )
		{
            $inner_name = $node->nodeValue;
        }
        
        $temp = '';
        do
        {
            $dgree .= $temp;
            $pos = strrpos( $inner_name, ' ' );
            $temp = substr( $inner_name, $pos );
            $inner_name = substr( $inner_name, 0, $pos );
        }
        while( $temp == strtoupper( $temp ));
        
        $inner_name .= $temp;
        $pos = strpos( $inner_name, ' ' );
        $dgree = trim( $dgree );
        $first_name = substr( $inner_name, 0, $pos );
        $last_name = trim( substr( $inner_name, $pos ) );

        
		// Get Other information
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if( substr( trim($node->nodeValue), 0, 12 ) == 'Specialties:' )
			{
				$speciality = $node->nextSibling->nodeValue;
			}

			if( getAttribute( $node, 'class' ) == 'panel-pane pane-entity-field pane-node-field-clinical-interest' )
			{
				$expertise = $node->nodeValue;
                $expertise = trim( str_replace( 'Clinical Interests:', '', $expertise ) );
			}   
            
			if( getAttribute( $node, 'class' ) == 'field field-name-field-physician-biography field-type-text-long field-label-hidden' )
			{
				$biology = $node->nodeValue;
			}

			if( getAttribute( $node, 'class' ) == 'field field-name-field-physician-locations field-type-field-collection field-label-hidden' )
			{
				$location = $node->nodeValue;
			}
            
			if( getAttribute( $node, 'class' ) == 'field field-name-field-research-interest field-type-text-long field-label-hidden' )
			{
				$research_interests = $node->nodeValue;
			}
            
			if( getAttribute( $node, 'class' ) == 'field field-name-field-physician-title field-type-text field-label-hidden' )
			{
				$appointment = $node->nodeValue;
			}            
            
		}
		
            // Get the education, Training and Certificate
		// Get Other information
		$node_list = $obj->getElementsByTagName( 'ul' );
		foreach( $node_list as $node )
		{
            switch( getAttribute( $node, 'class' ) )
            {            
                case 'field field-name-field-physician-education field-type-text field-label-above':
                    $education .= $node->nodeValue . ', ';
                    break;
                case 'field field-name-field-physician-residency field-type-text-long field-label-above':
                    $training .= 'Residency : ' . $node->nodeValue . ', ';
                    break;
                case 'field field-name-field-physician-fellowship field-type-text field-label-above':
                    $training .= 'Fellowship : ' . $node->nodeValue . ', ';
                    break;
                case 'field field-name-field-physician-internship field-type-text field-label-above':
                    $training .= 'Internship : ' . $node->nodeValue . ', ';
                    break;
                case 'field field-name-field-physician-boards field-type-text field-label-above':
                    $certification .= $node->nodeValue . ', ';
                    break;
            }
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
        
		// Extract phone from Location
		$location = str_replace( "\r\n", " ", $location );
		$location = str_replace( "\n", " ", $location );

		$pattern = '/Phone: *([0-9\-]+)/';
		$matches = '';
		$cntPhone = preg_match_all( $pattern, $location, $matches );
		if( $cntPhone > 0 )
		{
			for( $i = 0; $i < $cntPhone; $i ++ ) $phone .= $matches[1][$i] . ', ';
		}
		else
		{
			$phone = '';
		}
        
        if( strlen( $education ) > 0 ) $education = substr( $education, 0, strlen( $education ) - 2 );
        if( strlen( $training ) > 0 ) $training = substr( $training, 0, strlen( $training ) - 2 );
        if( strlen( $certification ) > 0 ) $certification = substr( $certification, 0, strlen( $certification ) - 2 );
        if( strlen( $zipcode ) > 0 ) $zipcode = substr( $zipcode, 0, strlen( $zipcode ) - 2 );
        if( strlen( $phone ) > 0 ) $phone = substr( $phone, 0, strlen( $phone ) - 2 );
    }
?>