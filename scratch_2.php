<?PHP
/*
	Title	: Define the scratch pattern
	Author	: Jubin Ri
	Created By : 2015.12.07
    Last Updated By : 2015.12.11
    
*/

    function process( $obj, $contents )
    {
		global $img_url, $biology, $expertise, $certification, $education, $location, $research_interests, $phone, $email, $appointment, $zipcode, $speciality, $training, $honor, $rank;

		// Get Image and expertise
		$node_list = $obj->getElementsByTagName( 'figure' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'class' ) == 'headshot' )
			{
				$img_url = 'https://winshipcancer.emory.edu/' . substr( getAttribute( $node->firstChild, 'src' ), 6 );
			}
		}
        
        // Get the Appointment Speciality and Expertise
		$node_list = $obj->getElementsByTagName( 'h2' );
		foreach( $node_list as $node )
		{
            if( trim($node->nodeValue) == 'Titles and Roles' )
            {
                $html = $obj->saveHTML( $node->nextSibling->firstChild );
                $html = str_replace( '<dt>', '', $html );
                $html = str_replace( '</dt>', ', ', $html );
                $html = str_replace( '<dd class="text-muted">', '', $html );
                $html = str_replace( '</dd>', ', ', $html );
                
                $pattern = ('/<dl[^>]*>([^<]*)<\/dl>/');
                $matches = '';
                if( preg_match_all( $pattern, $html, $matches) > 0 )
                {
                    $appointment = $matches[1][0];
                }

                $html = $obj->saveHTML( $node->nextSibling->nextSibling->firstChild );
                $pattern = ('/<dt>([^<]*)<\/dt>/');
                $matches = '';
                if( preg_match_all( $pattern, $html, $matches) > 0 )
                {
                    $speciality = $matches[1][0];
                }
                
                $pattern = '/<dd><a[^>]*>([^<]*)<\/a><\/dd>/';
                $matches = '';
                if( preg_match_all( $pattern, $html, $matches) > 0 )
                {
                    for( $i = 0; $i < count($matches[1]); $i ++ )
                        $expertise .= $matches[1][$i]. ', ';
                }   
            }
        }
        
		// Get the email and phone
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if( getAttribute( $node, 'id' ) == 'bio-primary-contact' )
			{
				$phone = $node->firstChild->nodeValue;
				$email = $node->firstChild->nextSibling->nodeValue;
			}
		}
		
		// Get the biology, Education, Research, Certificate
		$node_list = $obj->getElementsByTagName( 'section' );
		foreach( $node_list as $node )
		{
			$nodeValue = trim( $node->firstChild->nodeValue );
			if( $nodeValue == 'Biography' ) $biology = $node->firstChild->nextSibling->nodeValue;
			if( $nodeValue == 'Education' ) $education = $node->firstChild->nextSibling->nodeValue;
			if( $nodeValue == 'Research' ) $research_interests = $node->firstChild->nextSibling->nodeValue;
			if( $nodeValue == 'Awards' ) $honor = $node->firstChild->nextSibling->nodeValue;
		}
        
        // Get the rank
        if( strpos( $appointment, 'Associate Professor') !== false )
            $rank = 2;
        elseif( strpos( $appointment, 'Assistant Professor') !== false )
            $rank = 3;
        elseif( strpos( $appointment, 'Professor') !== false )
            $rank = 1;
    }

?>