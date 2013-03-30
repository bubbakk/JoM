    $(document).ajaxComplete(function(event, xhr, settings)
    {
        // get request parameters
        var domain  = getParameterByName(settings.url, 'd');
        var request = getParameterByName(settings.url, 'r');
        // JSON object data
        JSON_response = JSON.parse(xhr.responseText);

        switch ( domain + '/' + request )
        {
            case 'cat/lod':
                {
                    // parse the level
                    var level = getParameterByName(settings.url, 'l');
                    if ( parseInt(level, 10) === 1 ) {
                        JOM['new_job'].categories.categories      = JSON_response.data;
                        JOM['new_job'].set_categories_list();
                        JOM['new_job'].categories.nonce.nonce     = JSON_response.new_nonce;
                        JOM['new_job'].categories.nonce.timestamp = JSON_response.new_timestamp;
                    }
                    else
                    if ( parseInt(level, 10) === 2 ) {
                        JOM['new_job'].issues.categories      = JSON_response.data;
                        JOM['new_job'].set_issues_list();
                        JOM['new_job'].issues.nonce.nonce     = JSON_response.new_nonce;
                        JOM['new_job'].issues.nonce.timestamp = JSON_response.new_timestamp;
                    }
                    break;
                }
        }



    });
