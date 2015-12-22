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
			if(  getAttribute( $node, 'class' ) == 'img-right' ){
				$img_url = getAttribute( $node, 'src' );
			}
		}
		
		// biology
		$pattern = '/<h1>Biography<\/h1>([^<]*)<div>([^<]*)<h2>([^<]*)<\/h2>([^<]*)<p>([^<]*)<img ([^>]*)>([^<]*)<\/p>/';
		$matches = '';
		$cnt = preg_match_all( $pattern , $contents, $matches );
		
		if( $cnt == 1 )
		{
			$biology = $matches[7][0];
		}
		else
		{
			// Add a <p> open tag next to image tag
			$pattern = '/<h1>Biography<\/h1>([^<]*)<div>([^<]*)<h2>([^<]*)<\/h2>([^<]*)<p>([^<]*)<img ([^>]*)>([^<]*)<p>([^<]*)<\/p>/';
			$matches = '';
			$cnt = preg_match_all( $pattern , $contents, $matches );

//			var_dump( $matches );
			$biology = $matches[8][0];

//			echo $contents;
		}
        
		// Get Speciality
		$node_list = $obj->getElementsByTagName( 'h2' );
		foreach( $node_list as $node )
		{
            $speciality = $node->nodeValue;
            break;
        }        
		$speciality = substr( $speciality, strpos( $speciality, '(' ) + 1 );
		$speciality = substr( $speciality, 0, strlen( $speciality ) - 1 );

		// Get the Location and phone
		$node_list = $obj->getElementsByTagName( 'div' );
		foreach( $node_list as $node )
		{
			if(  getAttribute( $node, 'style' ) == 'margin-bottom:10px;' ){
				
				$location =  $node->nodeValue;
				$phone = substr( $location, strpos( $location, 'Phone' ) + 7 );
				$location = substr( $location, 0, strpos( $location, 'Phone' ) );
			}
		}
        
        // Get the zip code
        $pattern = '/([0-9][0-9][0-9][0-9][0-9]([0-9\-]*))/';
        $matches = '';
        if( preg_match_all( $pattern , $location, $matches ) > 0 )
        {
            $zipcode = $matches[1][0];
        }

    }
?>