<?PHP
/*
	Title	: Define the scratch pattern
	Author	: Jubin Ri
	Created By : 2015.12.07
    Last Updated By : 2015.12.11
    
*/

    function process( $obj, $contents )
    {
		global $img_url, $biology, $expertise, $certification, $education, $location, $research_interests, $phone, $email, $appointment, $zipcode, $speciality, $training, $honor, $rank, $dgree;


		// Get Image
		$pattern = '/<div class="profilePhotoContainer" id="provImage_MD">([^<]*)<img src="([^"]*)"([^>]*)>([^<]*)<\/div>/';
		$matches = '';
		preg_match_all( $pattern , $contents, $matches );

		$img_url = 'http://doctors.ucsd.edu' . $matches[2][0];
        
        // Get Dgree
        $node_list = $obj->getElementsByTagName( 'h1' );
		foreach( $node_list as $node )
        {
            $inner_name = $node->nodeValue;
            $dgree = trim( substr( $inner_name, strpos( $inner_name, ',') + 2 ) );
        }
        
        // Get Address
        $arrAddr = array();
        $node_list = $obj->getElementsByTagName( 'h3' );
		foreach( $node_list as $node )
        {
            $arrAddr['addr1'][] = $node->nodeValue;
        }
        
        $node_list = $obj->getElementsByTagName( 'span' );
		foreach( $node_list as $node )
        {
			switch( getAttribute( $node, 'itemprop' ) )
            {
                case 'addressLocality':
                    $arrAddr['addr4'][] = $node->nodeValue;
                    break;
                case 'addressRegion':
                    $arrAddr['addr5'][] = $node->nodeValue;
                    break;
                case 'postalCode':
                    $arrAddr['addr6'][] = $node->nodeValue;
                    break;

            }
        }

        
		// Get Other information
        $currentProfile = '';
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			switch( getAttribute( $node, 'id' ) )
			{
				case 'acadTitle':
					$appointment = $node->nodeValue;
					break;
				case 'providerSpec_MD':
					$speciality = $node->nodeValue;
					break;
				case 'tabs-1':
//					$biology = $node->nodeValue;
					break;
				case 'tabs-2':
					break;
				case 'tabs-3':
					$expertise = $node->nodeValue;
					break;
				case 'tabs-4':
//					$location = $node->nodeValue;
					break;
				case 'tabs-5':
					$phone = $node->nodeValue;
                    $phone = trim(str_replace( 'Contact Numbers', '', $phone ));
					break;
			}
            
            // Get the education, Training and Certificate
            if( getAttribute( $node, 'class' ) == 'profileLabel' )
            {
                $currentProfile = $node->nodeValue;
            }
            
            
            if( getAttribute( $node, 'class' ) == 'profileData' )
            {
                switch( $currentProfile )
                {            
                    case 'Medical Degree:':
                        $education .= $node->nodeValue . ', ';
                        break;
                    case 'Residency:':
                        $training .= 'Residency : ' . $node->nodeValue . ', ';
                        break;
                    case 'Fellowship:':
                        $training .= 'Fellowship : ' . $node->nodeValue . ', ';
                        break;
                    case 'Internship:':
                        $training .= 'Internship : ' . $node->nodeValue . ', ';
                        break;
                    case 'Board Certifications:':
                        $certification .= $node->nodeValue . ', ';
                        break;
                }
            }
            
            // Get the address 2, 3
            if( getAttribute( $node, 'class' ) == 'profileDataNoLabel emphasize' && getAttribute( $node, 'itemprop' ) == 'name' ) $arrAddr['addr2'][] = $node->nodeValue;
            if( getAttribute( $node, 'class' ) == 'profileDataNoLabel ' && getAttribute( $node, 'itemprop' ) == 'streetAddress' ) $arrAddr['addr3'][] = $node->nodeValue . ' ' . $node->nextSibling->nodeValue;
		}
        
        // Get  the biology
        $bioloby = '';
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'class' ) == 'profileDataNoLabel' )
			{
                if( strlen( $node->nodeValue ) > strlen( $biology) )
                {
                    $biology = $node->nodeValue;
                }
            }
        }
        $pos =  strrpos( $biology, 'UC San Diego Health');
        if( $pos > 0 ) $biology = substr( $biology, 0, $pos );
        
        // Build the address
        for( $i = 0; $i < count( $arrAddr['addr1']); $i ++ )
        {
            $location .= $arrAddr['addr1'][$i] . ' ' . $arrAddr['addr2'][$i] . ' ' . $arrAddr['addr3'][$i] . ' ' . $arrAddr['addr4'][$i] . ' ' . $arrAddr['addr5'][$i] . ' ' . $arrAddr['addr6'][$i] . ', ';
            $zipcode .= $arrAddr['addr6'][$i] . ', ';
        }
        
        // Get the rank
        if( strpos( $appointment, 'Associate Professor') !== false )
            $rank = 2;
        elseif( strpos( $appointment, 'Assistant Professor') !== false )
            $rank = 3;
        elseif( strpos( $appointment, 'Professor') !== false )
            $rank = 1;
        
        if( strlen( $education ) > 0 ) $education = substr( $education, 0, strlen( $education ) - 2 );
        if( strlen( $training ) > 0 ) $training = substr( $training, 0, strlen( $training ) - 2 );
        if( strlen( $certification ) > 0 ) $certification = substr( $certification, 0, strlen( $certification ) - 2 );
        if( strlen( $location ) > 0 ) $location = substr( $location, 0, strlen( $location ) - 2 );
        if( strlen( $zipcode ) > 0 ) $zipcode = substr( $zipcode, 0, strlen( $zipcode ) - 2 );
    }
?>