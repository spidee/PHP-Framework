<?php
    
    /* 
    * EXAMPLE SCRIPT - EXE script for any REGISTRATION FORM (use validations, save to DB)
    */ 
    
    $form = $HTTP_REQUEST;
    // get values only from $_POST
    $form->setSelector(HttpRequest::POST);
    
    // get session with default namespace
    $session = getSession();
    // save POST data from FORM to session - if user don't pass validation we use it in FORM template to fill inputs
    $session->form = $form->getPOSTdata();
    
    $validations = new Validator($form);
    
    // set rules for validation
    $validations->setRule('name'        , Validator::MUST_BE_STRING | Validator::MIN_STRING_LENGTH, 3, 'error msg for name');
    $validations->setRule('surname'     , Validator::MUST_BE_STRING | Validator::MIN_STRING_LENGTH, 3, 'error msg for surname');
    $validations->setRule('street'      , Validator::MIN_STRING_LENGTH, 3, 'error msg for street');
    $validations->setRule('streetnumber', Validator::REGULAR_EXPRESSION, "[0-9]+", 'error msg for streetnumber');
    $validations->setRule('city'        , Validator::MIN_STRING_LENGTH, 3, 'error msg for city');
    $validations->setRule('postcode'    , Validator::MUST_BE_NUMERIC | Validator::EXACT_STRING_LENGTH, 5, 'error msg for postcode');
       
    if (!$validations->validate())
        // set JavaScript alert message
        setFlashMessage($validations->getErrorMessages());
    else
    {
        // try to load item from DB table which has surname, street and city same as user filled in form
        $reg = new User("surname = '{$form->surname}' AND street = '{$form->street}' AND city = '{$form->city}'");
        
        // found someone? no? so save it to DB
        if (!$reg->isValid())
        {        
            // set DB table columns from form values
            $reg->name = $form->name;
            $reg->surname = $form->surname;
            $reg->street = $form->street;
            $reg->streetnumber = $form->streetnumber;
            $reg->city = $form->city;
            $reg->postcode = $form->postcode;
            $reg->userAgent = $HTTP_REQUEST->getUserAgent();
            $reg->ip = $HTTP_REQUEST->getUserIP();
            
            // Save to DB
            $reg->save();
            
            // set JavaScript alert message
            setFlashMessage("Your registration has been successful.");
            $session->form = null;
        }
        else
            setFlashMessage("You have already been registered.");
    }
    
    $form->setSelector(null);
    
    // back to the FORM
    reloadPage($SEO->getUrl('registration'));
 

?>
