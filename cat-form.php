<?php
use phpformbuilder\Form;
use phpformbuilder\Validator\Validator;

/* =============================================
    start session and include form class
============================================= */

session_start();
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';

/* =============================================
    validation if posted
============================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('contact-form-1') === true) {
    // create validator & auto-validate required fields
    $validator = Form::validate('contact-form-1');

    // additional validation
    $validator->maxLength(5000)->validate('message');
    $validator->email()->validate('email');

    // check for errors
    if ($validator->hasErrors()) {
        $_SESSION['errors']['contact-form-1'] = $validator->getAllErrors();
    } else {
        // Require composer autoload
        require_once __DIR__ . '/../home/vendor/autoload.php';
        // Create an instance of the class:
        $mpdf = new \Mpdf\Mpdf();
        $mpdf->SetHTMLHeader('
            <div style="text-align: right; font-weight: bold;">
                {PAGENO}
            </div>');
        $mpdf->SetHTMLFooter('<div style="text-align: right; font-weight: bold;">
                {DATE m-j-Y}
            </div>');
        date_default_timezone_set('America/New_York');
        $sub_time = date("l jS \of F Y | h:i:s A");
        $sub_time2 = date("F j Y");
        $file_name = 'FTBMR Application: '.$_POST['first-name'].' '.$_POST['last-name']. " " .$sub_time2.'';

        $html ='<html>
        <body>
            <h1><img src="https://static.wixstatic.com/media/fc2923_4d83694eb14c49fba29dd2c068b599e1~mv2.png/v1/fill/w_474,h_444,al_c,usm_0.66_1.00_0.01/fc2923_4d83694eb14c49fba29dd2c068b599e1~mv2.png" height="25px"> Free To Be Me Rescue</h1>
            <div>'."<b>Submitted on:</b> " .$sub_time.'</div>
            <div>'."<b>Over 18 Years Old:</b> " .$_POST['18-years-old'].'</div>

            <div>
                 <p><b>'."Name: </b>" .$_POST['first-name']." " .$_POST['last-name'].'</p>

                '."<b>Email:</b> " .$_POST['email']. "<br> 
                 <b>Phone Number:</b> " .$_POST['primary-phone']. " | <b>Phone Number:</b> " .$_POST['secondary-phone'].'
                 <br>
                <b>'."Address:</b> " .$_POST['street']. " " .$_POST['city']. ", " .$_POST['state']. " " .$_POST['zipcode'].'

                <hr style="height:1px;padding:0px;margin:0px;" />
                
                <b>'."Names of other adults residing with you:</b> " .$_POST['other-adults'].'
                
                <br><b>'."Number of children living in the house:</b> " .$_POST['number-of-children'].'

                <br><b>'."Ages:</b> " .$_POST['ages'].'
                
                <br><b>'."Do they live there full time:</b> " .$_POST['live-full-time'].'
                
                <br><b>'."Note any type of pet(s) they have shown fear of:</b> " .$_POST['shown-fear'].'
                
                <br><b>'."Do other children visit:</b> " .$_POST['other-children'].'
                    <br>&emsp; <b>'."How often do they visit:</b> " .$_POST['how-often-visit'].'
                
                <br><b>'."Does anyone in your household have allergies or asthma:</b> " .$_POST['allergies-asthma'].'
                
                <br>&emsp;<b>'."Does pets/dander trigger a reaction:</b> " .$_POST['allergies-asthma-yes'].'
                
                <br><b>'."Does your entire household know that you are considering adopting a pet:</b> " .$_POST['entire-house-know'].'
                
                <br>&emsp;<b>'."Why not:</b> " .$_POST['entire-house-know-no'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                '."<b>Where did you hear about us:</b> " .$_POST['where-did-you-hear-about-us']. 
                "<br><b>Have you spoken with a Volunteer:</b> " .$_POST['spoken-with-a-volunteer']. 
                "<br>&emsp;<b>Who:</b> " .$_POST['volunteer-spoken-with'].' </p>

                <hr style="height:1px;padding:0px;margin:0px;" />

                <p>'."<b>Why do you want to adopt a pet:</b> " .$_POST['why-do-you-want-to-adopt-a-pet'][0]. "|" .$_POST['why-do-you-want-to-adopt-a-pet'][1]. "|" .$_POST['why-do-you-want-to-adopt-a-pet'][2].
                "<br> <b>The pet will be primarily housed in: </b> " .$_POST['where-will-the-pet-be-primarily-housed'].' 
                <br> <b>'."Where will the pet sleep:</b> " .$_POST['where-will-pet-sleep'].'

                <br><b>'."Where will the pet be when no one is at home:</b> "  .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][0].  "|" .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][1]. "|" .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][2]. "|" .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][3]. "|" .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][4]. "|" .$_POST['where-will-your-pet-be-when-no-one-is-at-home'][5].'
                <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['where-will-your-pet-be-when-no-one-is-at-home-other'].'

                <br> <b>'."Interested in:</b> " .$_POST['is-there-a-particular-pet-you-are-interested-in'].'

                <br> <b>'."Breed preferences:</b> " .$_POST['please-note-your-pet-preferences'][0]. "|" .$_POST['please-note-your-pet-preferences'][1]. "|" .$_POST['please-note-your-pet-preferences'][2].'

                <br> <b>'."Breeds of interest:</b> " .$_POST['breeds-of-interest-to-you'].'

                <br> <b>'."Male or Female:</b> " .$_POST['male-or-female'].'

                <br> <b>'."Age Range:</b> " .$_POST['age-range'][0]. "|" .$_POST['age-range'][1]. "|" .$_POST['age-range'][2]. "|" .$_POST['age-range'][3].'

                <br> <b>'."Hair Type:</b> " .$_POST['hair-type'][0]. "|" .$_POST['hair-type'][1]. "|" .$_POST['hair-type'][2]. "|" .$_POST['hair-type'][3].'

                <br> <b>'."Would Consider:</b> " .$_POST['would-you-consider'][0]. "|" .$_POST['would-you-consider'][1]. "|" .$_POST['would-you-consider'][2].'

                <br> <b>'."Size:</b> " .$_POST['size'][0]. "|" .$_POST['size'][1]. "|" .$_POST['size'][2].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."How long will the pet be left outside without supervision:</b> " .$_POST['how-long-would-the-pet-be-left-outside-without-supervision'].'

                <br><b>'."When left outside alone, what type of shelter is there:</b> " .$_POST['when-left-out-alone-for-this-period-what-type-of-shelter-is-available'][0]. "|" .$_POST['when-left-out-alone-for-this-period-what-type-of-shelter-is-available'][1]. "|" .$_POST['when-left-out-alone-for-this-period-what-type-of-shelter-is-available'][2]. "|" .$_POST['when-left-out-alone-for-this-period-what-type-of-shelter-is-available'][3].' 
                        <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['when-left-out-alone-for-this-period-what-type-of-shelter-is-available-other'].'

                <br><b>'."Problems or situations that would make :</b> " .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][0]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][1]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][2]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][3]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][4]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][5]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][6]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][7]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][8]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][9]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][10]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][11]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][12]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][13]. "|" .$_POST['what-problems-or-situations-would-make-you-return-a-pet'][14].'
                        <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['what-problems-or-situations-would-make-you-return-a-pet-other'].'

                <br><b>'."Describe your homes activity level:</b> " .$_POST['describe-your-homes-activity-level'].'
                    <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['describe-your-homes-activity-level-other'].'

                <br><b>'."Do you feel a pet should be spayed or neutered:</b> " .$_POST['do-you-feel-a-pet-should-be-spayed-or-neutered'].'
                    <br>&emsp;<b>Why not:</b><br>&emsp;&emsp;&emsp;  '.$_POST['why-not'].'

                <br><b>'."Approximately how many hours would the pet be left alone:</b> " .$_POST['approximately-how-many-hours-would-the-pet-be-left-alone'].'

                <br><b>'."How would you discipline a pet that chewed your personal belongings or damaged the furniture:</b> " .$_POST['how-would-you-discipline-a-pet-that-chewed-your-personal-belongings-or-clawed-the-furniture'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."Who will be responsible for daily pet care:</b> " .$_POST['who-will-be-responsible-for-daily-pet-care'].'

                <br><b>'."Caregiver when primary person is away:</b> " .$_POST['caregiver-when-primary-person-is-away'].'

                <br><b>'."If you adopt a pet from us, will you:</b> " .$_POST['if-you-adopt-a-pet-from-us-will-you'].'
                    <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['if-you-adopt-a-pet-from-us-will-you-other'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."Please list pets currently owned and provide requested information:</b> " .$_POST['please-list-pets-currently-owned-and-provide-requested-information'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."Please list all pets previously owned in the past 5 years and describe what happened to them:</b> " .$_POST['please-list-all-pets-previously-owned-in-the-past-5-years-and-describe-what-happened-to-them'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."Please list several preferences for a home visit:</b> " .$_POST['please-list-several-preferences-for-a-home-visit'].'

                <br><b>'."When is the best time to call to see how the pet is adjusting:</b> " .$_POST['when-is-the-best-time-to-call-to-see-how-the-pet-is-adjusting'].'

                <br><b>'."Is there an answering machine/voicemail:</b> " .$_POST['is-there-an-answering-machine/voicemail'].'

                <br><b>'."To help locate the best pet for you, will you permit us to share your application with other rescue groups:</b> " .$_POST['to-help-locate-the-best-pet-for-you-will-you-permit-us-to-share-your-application-with-other-rescue-groups'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <br><b>'."Do you live in a:</b> " .$_POST['do-you-live-in-a'].'
                    <br>&emsp;<b>Other:</b><br>&emsp;&emsp;&emsp;  '.$_POST['do-you-live-in-a-other'].'
                        <br>&emsp;&emsp;<b>Do you own or rent:</b><br>&emsp;&emsp;&emsp;  '.$_POST['do-you-live-in-a-own-rent'].'
                            <br>&emsp;&emsp;&emsp;<b>Landlord Name:</b><br>&emsp;&emsp;&emsp;  '.$_POST['landlord-name'].'
                            <br>&emsp;&emsp;&emsp;<b>Landlord Phone Number:</b><br>&emsp;&emsp;&emsp;  '.$_POST['landlord-phone'].'
                            <br>&emsp;&emsp;&emsp;<b>Is there any restrictions on pet(s) weight, type, breed, etc. from the Landlord:</b><br>&emsp;&emsp;&emsp;  '.$_POST['landlord-restrictions'].'

                <hr style="height:1px;padding:0px;margin:0px;" />

                <h3>References</h3>

                <br><b>'."Reference Name:</b> " .$_POST['reference-name'].'

                <br><b>'."Reference Phone Number:</b> " .$_POST['reference-phone'].'

                <br><b>'."Reference Address:</b> " .$_POST['reference-address'].'

                <br><b>'."Veterinarian Name:</b> " .$_POST['vet-name'].'

                <br><b>'."Veterinarian Phone Number:</b> " .$_POST['vet-phone'].'

                <br><b>'."Veterinarian Address:</b> " .$_POST['vet-address'].'

                <br>'."" .$_POST['I-agree-to-call-my-vet-to-release-medical-history-to-a-Free-To-Be-Me-Rescue-representative'].'

                <br><b>'."Anything Else:</b> " .$_POST['message'].'
            </div>

        </body>
        </html>';
        // Write some HTML code:
        $mpdf->WriteHTML($html);
        $location = __DIR__ .'/assets/pdf/';
        // Output a PDF file directly to the browser
        $mpdf->Output($location .$file_name.'.pdf', \Mpdf\Output\Destination::FILE);
        //$mpdf->Output();
        $email_config = array(
            'sender_email'    => 'cats@freetobemerescue.org',
            'sender_name'     => 'New Cat Appication',
            'reply_to_email'  => 'freetobemerescue@gmail.com',
            'recipient_email' => addslashes($_POST['email']),
            //'template'        => 'default-styled.html',
            'subject'         => 'New Adoption Application',
            'attachments'     => ''.$location.''.$file_name.'.pdf',
            'filter_values'   => 'contact-form-1'
        );
        $sent_message = Form::sendMail($email_config);
        //Form::clear('contact-form-1');
    }
}

/* ==================================================
    The Form
================================================== */

$form = new Form('contact-form-1', 'horizontal', 'novalidate', 'bs4');
$form->startFieldset('Please fill in this form to submit an application');
$form->addHtml('<p>Please remember that we only adopt within 1 hour of Albany, NY.</p>');
$form->addHtml('<p>Pet ownership is a long-term commitment that an entire household should be willing to participate in before agreeing to bring a pet home. This application will help you determine what type of pet you are looking for and if you are ready to properly care for a pet. It will also help match you with the best possible pet for your lifestyle. Please fill out the application accurately and entirely and do not hesitate to call with any questions.</p>');

//18 Years Old
$form->addRadio('18-years-old', 'Yes', 'Yes');
$form->addRadio('18-years-old', 'No', 'No');
$form->printRadioGroup('18-years-old', 'Are you at least 18 years old?', true, 'false');
$form->addHtml('<hr>');
//Name group
$form->groupInputs('first-name', 'last-name');
$form->setCols(-1, -1);
//First Name
$form->addIcon('first-name', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'first-name', '', '', 'falseded, placeholder=First Name*');
//Last Name
$form->addIcon('last-name', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'last-name', '', '', 'falseded, placeholder=Last Name*');

//Email and Phone and Cell Phone
$form->groupInputs('email', 'primary-phone', 'secondary-phone');
$form->setCols(-1, -1);
//Email Address
$form->addIcon('email', '<i class="fa fa-envelope" aria-hidden="true"></i>', 'before');
$form->addInput('email', 'email', '', '', 'falseded, placeholder=Email Address*');
//Home Phone
$form->addIcon('primary-phone', '<i class="fa fa-phone" aria-hidden="true"></i>', 'before');
$form->addInput('tel', 'primary-phone', '', '', 'falseded, placeholder=Primary Phone Number*');
//Cell Phone
$form->addIcon('secondary-phone', '<i class="fa fa-phone" aria-hidden="true"></i>', 'before');
$form->addInput('tel', 'secondary-phone', '', '', 'placeholder=Secondary Phone Number');

//Address, City, State, Zipcode group
$form->groupInputs('street', 'city', 'state', 'zipcode');
$form->setCols(-1, -1);
//Address
$form->addIcon('street', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'street', '', '', 'falseded, placeholder=Address*');
//Address City
$form->addIcon('city', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'city', '', '', 'falseded, placeholder=City*');
//Address State
$form->addIcon('state', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'state', '', '', 'falseded, placeholder=State*');
//Address Zipcode
$form->addIcon('zipcode', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'zipcode', '', '', 'falseded, placeholder=Zipcode*');
$form->addHtml('<hr>');

//Names of other adults residing with you
//$form->addIcon('other-adults', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'other-adults', '', 'Other adults residing with you:', 'falseded');
$form->addHtml('<hr>');

//Names of other adults residing with you
$form->addInput('text', 'number-of-children', '', 'Number of children living in the house:', 'falseded');
$form->addHtml('<hr>');

//Do they live there full time
$form->addRadio('live-full-time', 'Yes', 'Yes');
$form->addRadio('live-full-time', 'No', 'No');
$form->printRadioGroup('live-full-time', 'Do they live there full time?', true, 'falseded');

//Where did you hear about us?
$form->addInput('text', 'shown-fear', '', 'Note any type of pet(s) they have shown fear of:', 'falseded');
$form->addHtml('<hr>');

//Do other children visit?
$form->addRadio('other-children', 'Yes', 'Yes');
$form->addRadio('other-children', 'No', 'No');
$form->printRadioGroup('other-children', 'Do other children visit?', true, 'false');
$form->startDependentFields('other-children', 'Yes');
$form->addInput('text', 'other-children-ages', '', 'What ages are they?', 'falseded');
$form->addInput('text', 'how-often-visit', '', 'How often do they visit?', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

$form->addRadio('allergies-asthma', 'Yes', 'Yes');
$form->addRadio('allergies-asthma', 'No', 'No');
$form->printRadioGroup('allergies-asthma', 'Does anyone in your household have allergies or asthma?', true, 'false');
$form->startDependentFields('allergies-asthma', 'Yes');
$form->addInput('text', 'allergies-asthma-yes', '', 'Does pets/dander trigger a reaction?', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

$form->addRadio('entire-house-know', 'Yes', 'Yes');
$form->addRadio('entire-house-know', 'No', 'No');
$form->printRadioGroup('entire-house-know', 'Does your entire household know that you are considering adopting a pet?', true, 'false');
$form->startDependentFields('entire-house-know', 'No');
$form->addInput('text', 'entire-house-know-no', '', 'Why not?', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//Where did you hear about us?
$form->addInput('text', 'where-did-you-hear-about-us', '', 'Where did you hear about us?', 'falseded');
$form->addHtml('<hr>');

//Have you spoken with a Volunteer here?
$form->addRadio('spoken-with-a-volunteer', 'Yes', 'Yes');
$form->addRadio('spoken-with-a-volunteer', 'No', 'No');
$form->printRadioGroup('spoken-with-a-volunteer', 'Have you spoken with a Volunteer here?', true, 'falseded');
$form->startDependentFields('spoken-with-a-volunteer', 'Yes');
$form->addHelper("Type in the name of the person you spoke to it you know it.", 'volunteer-spoken-with');
$form->addInput('text', 'volunteer-spoken-with', '', 'Volunteer Name', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');
$complete_list2 = [
    '%availableTags%' =>
    '
    "Not Sure",
    "Lisa N.",
    "Lisa C.",
    "Kathy",
    "Nancy",
    '
];
$form->addPlugin('autocomplete', '#volunteer-spoken-with', 'default', $complete_list2);

//Why do you want to adopt a pet?
$form->addCheckbox('why-do-you-want-to-adopt-a-pet', 'Pest Control', 'Pest Control');
$form->addCheckbox('why-do-you-want-to-adopt-a-pet', 'Companionship', 'Companionship');
$form->addCheckbox('why-do-you-want-to-adopt-a-pet', 'Breeding', 'Breeding');
$form->printCheckboxGroup('why-do-you-want-to-adopt-a-pet', 'Why do you want to adopt a pet?', true, 'falseded');
$form->addHtml('<hr>');

//Where will the pet be primarily housed?
$form->addInput('text', 'where-will-the-pet-be-primarily-housed', '', 'Where will the pet be primarily housed?');
$form->addHtml('<hr>');

//Where will pet sleep?
$form->addInput('text', 'where-will-pet-sleep', '', 'Where will pet sleep?');
$form->addHtml('<hr>');

//Where will your pet be when no one is at home?
$form->addCheckbox('where-will-your-pet-be-when-no-one-is-at-home', 'Loose outside', 'Loose outside');
$form->addCheckbox('where-will-your-pet-be-when-no-one-is-at-home', 'Loose inside', 'Loose inside');
$form->addCheckbox('where-will-your-pet-be-when-no-one-is-at-home', 'Crated or otherwise confined inside', 'Crated or otherwise confined inside');
$form->addCheckbox('where-will-your-pet-be-when-no-one-is-at-home', 'Other', 'Other');
$form->printCheckboxGroup('where-will-your-pet-be-when-no-one-is-at-home', 'Where will your pet be when no one is at home?', true, 'falseded');
$form->startDependentFields('where-will-your-pet-be-when-no-one-is-at-home', 'Other');
$form->addInput('text', 'where-will-your-pet-be-when-no-one-is-at-home-other', '', 'Where?', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//Is there a particular pet you are interested in?
$form->addInput('text', 'is-there-a-particular-pet-you-are-interested-in', '', 'Is there a particular pet you are interested in?', 'placeholder=Name or Names');
$form->addHtml('<hr>');

//Please note your pet preferences
$form->addCheckbox('please-note-your-pet-preferences', 'Purebred', 'Purebred');
$form->addCheckbox('please-note-your-pet-preferences', 'Mixed Breed', 'Mixed Breed');
$form->addCheckbox('please-note-your-pet-preferences', 'No preference', 'No preference');
$form->printCheckboxGroup('please-note-your-pet-preferences', 'Please note your pet preferences', true, 'falseded');
$form->addHtml('<hr>');

//Male or Female
$form->addRadio('male-or-female', 'Male', 'Male');
$form->addRadio('male-or-female', 'Female', 'Female');
$form->addRadio('male-or-female', 'No Preference', 'No Preference');
$form->printRadioGroup('male-or-female', 'Male or Female?', true, 'falseded');
$form->addHtml('<hr>');

//Age range CHANGE FOR CATS - ASK ABOUT AGES
$form->addCheckbox('age-range', 'Kitten', 'Kitten');
$form->addCheckbox('age-range', 'Young', 'Young');
$form->addCheckbox('age-range', 'Senior', 'Senior');
$form->addCheckbox('age-range', 'No Preference', 'No Preference');
$form->printCheckboxGroup('age-range', 'Age range', true, 'falseded');
$form->addHtml('<hr>');

//Hair Type
$form->addCheckbox('hair-type', 'Long Hair', 'Long Hair');
$form->addCheckbox('hair-type', 'Short Hair', 'Short Hair');
$form->addCheckbox('hair-type', 'Hypoallergenic', 'Hypoallergenic');
$form->addCheckbox('hair-type', 'No Preference', 'No Preference');
$form->printCheckboxGroup('hair-type', 'Hair Type', true, 'falseded');
$form->addHtml('<hr>');

//Would you consider
$form->addCheckbox('would-you-consider', 'Special Needs Pet', 'Special Needs Pet');
$form->addCheckbox('would-you-consider', 'Adopting 2 Pets', 'Adopting 2 Pets');
$form->addCheckbox('would-you-consider', 'Senior Pets', 'Senior Pets');
$form->printCheckboxGroup('would-you-consider', 'Would you consider', true);
$form->addHtml('<hr>');

//Size
$form->addCheckbox('size', 'Small (up to 10 lbs)', 'Small (up to 10 lbs)');
$form->addCheckbox('size', 'Large (10-20 lbs)', 'Large (10-20 lbs)');
$form->addCheckbox('size', 'No Preference', 'No Preference');
$form->printCheckboxGroup('size', 'Size', false, 'falseded');
$form->addHtml('<hr>');

//How long would the pet be left outside without supervision?
$form->addInput('text', 'how-long-would-the-pet-be-left-outside-without-supervision', '', 'How long would the pet be left outside without supervision?', 'falseded');
$form->addHtml('<hr>');

//When left out alone for this period what type of shelter is available?
$form->addCheckbox('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'Shade tree', 'Shade tree');
$form->addCheckbox('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'Covered Area', 'Covered Area');
$form->addCheckbox('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'Shed', 'Shed');
$form->addCheckbox('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'Other', 'Other');
$form->printCheckboxGroup('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'When left out alone for this period what type of shelter is available?', true);
$form->startDependentFields('when-left-out-alone-for-this-period-what-type-of-shelter-is-available', 'Other');
$form->addInput('text', 'when-left-out-alone-for-this-period-what-type-of-shelter-is-available-other', '', 'Other', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//What problems or situations would make you return a pet?
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Nothing', 'Nothing');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Housebreaking', 'Housebreaking');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Jumping up', 'Jumping up');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Shyness/other fears', 'Shyness/other fears');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Shedding', 'Shedding');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Digging', 'Digging');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Pet’s activity level', 'Pet’s activity level');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Scratching/Climbing on furniture', 'Scratching/Climbing on furniture');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Moving', 'Moving');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'New Baby', 'New Baby');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Personal illness', 'Personal illness');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Divorce', 'Divorce');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Chewing', 'Chewing');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Cost of Pet Care', 'Cost of Pet Care');
$form->addCheckbox('what-problems-or-situations-would-make-you-return-a-pet', 'Other', 'Other');
$form->printCheckboxGroup('what-problems-or-situations-would-make-you-return-a-pet', 'What problems or situations would make you return a pet?', false);
$form->startDependentFields('what-problems-or-situations-would-make-you-return-a-pet', 'Other');
$form->addInput('text', 'what-problems-or-situations-would-make-you-return-a-pet-other', '', 'Other', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//Describe your homes activity level
$form->addRadio('describe-your-homes-activity-level', 'Busy/active/noisy', 'Busy/active/noisy');
$form->addRadio('describe-your-homes-activity-level', 'Moderate comings/goings', 'Moderate comings/goings');
$form->addRadio('describe-your-homes-activity-level', 'Quiet occasional guests', 'Quiet occasional guests');
$form->addRadio('describe-your-homes-activity-level', 'Other', 'Other');
$form->printRadioGroup('describe-your-homes-activity-level', 'Describe your homes activity level', true, 'falseded');
$form->startDependentFields('describe-your-homes-activity-level', 'Other');
$form->addInput('text', 'describe-your-homes-activity-level-other', '', 'Describe your homes activity level', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//Do you feel a pet should be spayed or neutered?
$form->addRadio('do-you-feel-a-pet-should-be-spayed-or-neutered', 'Yes', 'Yes');
$form->addRadio('do-you-feel-a-pet-should-be-spayed-or-neutered', 'No', 'No');
$form->printRadioGroup('do-you-feel-a-pet-should-be-spayed-or-neutered', 'Do you feel a pet should be spayed or neutered?', true, 'falseded');
$form->startDependentFields('do-you-feel-a-pet-should-be-spayed-or-neutered', 'No');
$form->addInput('text', 'why-not', '', 'Why not?', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

//Approximately how many hours would the pet be left alone?
//$form->addHtml('<p>Approximately how many hours would the pet be left alone?</p>');
$form->addHelper('Weekday and weekend times', 'approximately-how-many-hours-would-the-pet-be-left-alone');
$form->addInput('text', 'approximately-how-many-hours-would-the-pet-be-left-alone', '', 'Approximately how many hours would the pet be left alone?', 'falseded');
$form->addHtml('<hr>');

//How would you discipline a pet that chewed your personal belongings or clawed the furniture?
$form->addInput('text', 'how-would-you-discipline-a-pet-that-chewed-your-personal-belongings-or-clawed-the-furniture', '', 'How would you discipline a pet that chewed your personal belongings or clawed the furniture?', 'falseded');
$form->addHtml('<hr>');

//Who will be responsible for daily pet care?
$form->addInput('text', 'who-will-be-responsible-for-daily-pet-care', '', 'Who will be responsible for daily pet care?', 'falseded');
$form->addHtml('<hr>');

//Caregiver when primary person is away.
$form->addInput('text', 'caregiver-when-primary-person-is-away', '', 'Caregiver when primary person is away?', 'falseded');
$form->addHtml('<hr>');

//If you adopt a pet from us, will you
$form->addRadio('if-you-adopt-a-pet-from-us-will-you', 'Declaw', 'Declaw');
$form->addRadio('if-you-adopt-a-pet-from-us-will-you', 'Dock Tail', 'Dock Tail');
$form->addRadio('if-you-adopt-a-pet-from-us-will-you', 'Dock Ears', 'Dock Ears');
$form->addRadio('if-you-adopt-a-pet-from-us-will-you', 'None of the above', 'None of the above');
$form->addRadio('if-you-adopt-a-pet-from-us-will-you', 'Other', 'Other');
$form->printRadioGroup('if-you-adopt-a-pet-from-us-will-you', 'If you adopt a pet from us, will you', true, 'falseded');
$form->startDependentFields('if-you-adopt-a-pet-from-us-will-you', 'Other');
$form->addInput('text', 'if-you-adopt-a-pet-from-us-will-you-other', '', 'Other', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');



//Please list pets currently owned and provide requested information:
$form->addHtml('<p>Please list pets currently owned and provide requested information:</p>');
$form->addHelper('Name, Type, Age, Spayed or Neutered', 'please-list-pets-currently-owned-and-provide-requested-information');
$form->addTextarea('please-list-pets-currently-owned-and-provide-requested-information', '', '', 'cols=30, rows=4, falseded');
$form->addHtml('<hr>');

//Please list all pets previously owned in the past 5 years and describe what happened to them.
$form->addHtml('<p>Please list all pets previously owned in the past 5 years and describe what happened to them.</p>');
$form->addHelper('Not including not fish or reptiles.', 'please-list-all-pets-previously-owned-in-the-past-5-years-and-describe-what-happened-to-them');
$form->addTextarea('please-list-all-pets-previously-owned-in-the-past-5-years-and-describe-what-happened-to-them', '', '', 'cols=30, rows=4, falseded');
$form->addHtml('<hr>');

//Please list several preferences for a home visit
$form->addHelper('Times and Day of the Week', 'please-list-several-preferences-for-a-home-visit');
$form->addInput('text', 'please-list-several-preferences-for-a-home-visit', '', 'Please list several preferences for a home visit', 'falseded');
$form->addHtml('<hr>');

//When is the best time to call to see how the pet is adjusting?
$form->addInput('text', 'when-is-the-best-time-to-call-to-see-how-the-pet-is-adjusting', '', 'When is the best time to call to see how the pet is adjusting?', 'falseded');
$form->addHtml('<hr>');

//Is there an answering machine/voicemail?
$form->addRadio('is-there-an-answering-machine/voicemail', 'Yes', 'Yes');
$form->addRadio('is-there-an-answering-machine/voicemail', 'No', 'No');
$form->printRadioGroup('is-there-an-answering-machine/voicemail', 'Is there an answering machine/voicemail?', true, 'false');
$form->addHtml('<hr>');

//To help locate the best pet for you, will you permit us to share your application with other rescue groups?
$form->addRadio('to-help-locate-the-best-pet-for-you-will-you-permit-us-to-share-your-application-with-other-rescue-groups', 'Yes', 'Yes');
$form->addRadio('to-help-locate-the-best-pet-for-you-will-you-permit-us-to-share-your-application-with-other-rescue-groups', 'No', 'No');
$form->printRadioGroup('to-help-locate-the-best-pet-for-you-will-you-permit-us-to-share-your-application-with-other-rescue-groups', 'To help locate the best pet for you, will you permit us to share your application with other rescue groups?', true, 'false');
$form->addHtml('<hr>');

//Do you live in a
$form->addRadio('do-you-live-in-a', 'House', 'House');
$form->addRadio('do-you-live-in-a', 'Apartment', 'Apartment');
$form->addRadio('do-you-live-in-a', 'Mobile Home', 'Mobile Home');
$form->addRadio('do-you-live-in-a', 'Other', 'Other');
$form->printRadioGroup('do-you-live-in-a', 'Do you live in a', true, 'falseded');
$form->startDependentFields('do-you-live-in-a', 'Other');
$form->addInput('text', 'do-you-live-in-a-other', '', 'Other', 'falseded');
$form->endDependentFields();
$form->startDependentFields('do-you-live-in-a', 'House, Apartment, Mobile Home, Other');
$form->addRadio('do-you-live-in-a-own-rent', 'Own', 'Own');
$form->addRadio('do-you-live-in-a-own-rent', 'Rent', 'Rent');
$form->printRadioGroup('do-you-live-in-a-own-rent', 'Do you own or rent?', true, 'falseded');
$form->addHtml('<hr>');
$form->startDependentFields('do-you-live-in-a-own-rent', 'Rent');
$form->addHtml('<p>You MUST have proof that you are allowed pets on the premise from the owner.</p>');
$form->addIcon('landlord-name', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'landlord-name', '', 'Landlord Name', 'falseded');
$form->addIcon('landlord-phone', '<i class="fa fa-phone" aria-hidden="true"></i>', 'before');
$form->addInput('tel', 'landlord-phone', '', 'Landlord Phone Number', 'falseded');
$form->addInput('text', 'landlord-restrictions', '', 'Is there any restrictions on pet(s) weight, type, breed, ect. from the Landlord?', 'falseded');
$form->endDependentFields();
$form->endDependentFields();
$form->addHtml('<hr>');

//Is there a leash law in your town?
$form->addRadio('is-there-a-leash-law-in-your-town', 'Yes', 'Yes');
$form->addRadio('is-there-a-leash-law-in-your-town', 'No', 'No');
$form->addRadio('is-there-a-leash-law-in-your-town', 'Other', 'Other');
$form->printRadioGroup('is-there-a-leash-law-in-your-town', 'Is there a leash law in your town?', true, 'falseded');
$form->startDependentFields('is-there-a-leash-law-in-your-town', 'Other');
//Is there a barrier to keep pet out?
$form->addInput('text', 'is-there-a-leash-law-in-your-town-other', '', 'Other', 'falseded');
$form->endDependentFields();
$form->addHtml('<hr>');

$form->addHtml('<p style="text-align:center;"><strong>References</strong></p>');
$form->addHtml('<hr>');

//Personal Reference - Name
$form->addHelper('Not a relative or someone living with you', 'reference-name');
$form->addIcon('reference-name', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'reference-name', '', 'Reference Name', 'falseded');
$form->addHtml('<hr>');

//Personal Reference - Phone Number
$form->addIcon('reference-phone', '<i class="fa fa-phone" aria-hidden="true"></i>', 'before');
$form->addInput('tel', 'reference-phone', '', 'Reference Phone Number', 'falseded');
$form->addHtml('<hr>');

//Personal Reference - Address
$form->addIcon('reference-address', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'reference-address', '', 'Reference Address', 'falseded');
$form->addHtml('<hr>');

//Veterinarian Name
$form->addHelper('If you dont yet have a vet, enter something like: "I am going to contact one."', 'vet-name');
$form->addIcon('vet-name', '<i class="fa fa-user" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'vet-name', '', 'Veterinarian Name', 'required');
$complete_list = [
    '%availableTags%' =>
    '
    "I am going to contact one",
    "Adirondack Animal Hospital",
    "Albany County veterinarian",
    "Amsterdam Animal Hospital",
    "Animal Care Hospital of Clifton Park",
    "Aqueduct Animal Hospital",
    "Banfield Pet Hospital",
    "Berkshire Veterinary Hospital",
    "Bethlehem Veterinary Hospital",
    "Bloomingrove Veterinary Hospital",
    "Boght Veterinary Clinic",
    "Burnt Hills Veterinary Hospital",
    "Canterbury Animal Hospital",
    "Catskill Animal Hospital",
    "Central Veterinary Hospital",
    "Chathams Small Animal Hospital",
    "Clifton Park Veterinary Clinic",
    "Cobleskill Veterinary Clinic",
    "Colonie Animal Hospital",
    "Countryside Veterinary Hospital",
    "Delmar Animal Hospital",
    "Drumm Veterinary Hospital",
    "East Greenbush Animal Hospital",
    "Glens Falls Animal Hospital",
    "Glenville Veterinary Clinic",
    "Greenfield Animal Hospital",
    "Greylock Animal Hospital",
    "Guilderland Animal Hospital",
    "Harmony Veterinary Clinic",
    "Haven Animal Hospital",
    "Homestead Animal Hospital",
    "Howes Cave Animal Hospital",
    "Kinderhook animal hospital",
    "Latham Animal Hospital",
    "Mandak Veterinary Services",
    "Miller Animal Hospital",
    "Milton Veterinary Hospital",
    "Mountainview Animal Hospital",
    "Nassau Veterinary Clinic",
    "New Baltimore Animal Hospital",
    "Oakwood Veterinary Clinic",
    "Parkside Veterinary Hospital",
    "River Road Animal Hospital",
    "Rotterdam Veterinary Hospital",
    "Sand Creek Animal Hospital",
    "Sandcreek Animal Hospital",
    "Shaker Animal Hospital",
    "The Animal Hospital",
    "The Village Animal Clinic",
    '
];
$form->addPlugin('autocomplete', '#vet-name', 'default', $complete_list);
$form->addHtml('<hr>');

//Veterinarian Phone Number
$form->addIcon('vet-phone', '<i class="fa fa-phone" aria-hidden="true"></i>', 'before');
$form->addInput('tel', 'vet-phone', '', 'Veterinarian Phone Number');
$form->addHtml('<hr>');

//Veterinarian Address
$form->addIcon('vet-address', '<i class="fa fa-home" aria-hidden="true"></i>', 'before');
$form->addInput('text', 'vet-address', '', 'Veterinarian Address', 'falseded');
$form->addHtml('<hr>');

//I agree to call my vet to release medical history to a Free To Be Me Rescue representative.
$form->addHtml('<p style="text-align:center;"><strong>Authorization</strong></p>');
$form->addHtml('<p style="text-align:center;"><strong>Call your vet!</strong></p>');
$form->addHtml('<p style="text-align:center;"><strong>Your application CAN NOT be processed until you CALL the vet!</strong></p>');
$form->addCheckbox('I-agree-to-call-my-vet-to-release-medical-history-to-a-Free-To-Be-Me-Rescue-representative', 'I agree to call my vet to release medical history to a Free To Be Me Rescue representative.', 'I agree to call my vet to release medical history to a Free To Be Me Rescue representative.');
$form->printCheckboxGroup('I-agree-to-call-my-vet-to-release-medical-history-to-a-Free-To-Be-Me-Rescue-representative', '', true, 'falseded');



$form->addHtml('<hr>');
//Anything Else
$form->addHtml('<p>Anything Else?</p>');
$form->addTextarea('message', '', '', 'cols=30, rows=4, falseded, placeholder=Anything Else?');

//Info
$form->addHtml('<p style="text-align:center;"><strong>Information</strong></p><p>If an adopter cannot keep the pet for its lifetime, the pet MUST be returned to Free To Be Me Rescue under the conditions specified in the Animal Adoption Contract. If the pet is not yet spayed or neutered, an approved adopter may only foster the pet until the procedure is done by a veterinarian chosen by Free To Be Me Rescue (per the Unaltered Foster Care Agreement) after which the pet may be adopted, at Free To Be Me Rescue discretion. Each pet adoption is assessed a Non-refundable fee which helps pay for the medical and other expenses incurred by Free To Be Me Rescue on behalf of the pet. By signing below I acknowledge that I have completely read the Application, comprehend it fully, and know that applying does not insure approval and that my answers will be taken at face value. I also understand that Free To Be Me Rescue reserves the right to disapprove any applicant for any reason and that untruthful answers or failure to comply with the requirements of this Application, the Foster Agreement, or the Adoption Contract may result in forfeiture of any Free To Be Me Rescue pet which may be fostered and/or adopted by me.</p>');

//Submit
$form->addBtn('submit', 'submit-btn', 1, 'Send <i class="fa fa-envelope append" aria-hidden="true"></i>', 'class=btn btn-success ladda-button, data-style=zoom-in', 'my-btn-group');
$form->printBtnGroup('my-btn-group');
$form->endFieldset();

// word-character-count plugin
$form->addPlugin('word-character-count', '#message', 'default', array('%maxAuthorized%' => 5000));

// Custom radio & checkbox css
$form->addPlugin('nice-check', 'form', 'default', ['%skin%' => 'green']);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="style.css" type="text/css"> 
  <?php $form->printIncludes('css');?>
  </head>

<body class="bg-info">
    <nav class="navbar navbar-expand-md bg-dark navbar-dark">
    <div class="container">
      <a class="navbar-brand" href="http://c0255.paas3.tx.modxcloud.com/">
        <img src="images/FTBMR_Logo No Background.png" width="30" height="30" class="d-inline-block align-top bg-light rounded" alt=""> </a>
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar3SupportedContent" aria-controls="navbar3SupportedContent" aria-expanded="false" aria-label="Toggle navigation"> <span class="navbar-toggler-icon"></span> </button>
      <div class="collapse navbar-collapse text-center justify-content-center" id="navbar3SupportedContent">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link text-white" href="http://c0255.paas3.tx.modxcloud.com/">Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="about">About</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-white" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> Adoptable Pets</a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
              <a class="dropdown-item" href="https://www.petfinder.com/search/pets-for-adoption/?shelter_id%5B0%5D=NY1257">Available Pets</a>
              <a class="dropdown-item" href="https://www.petfinder.com/search/pets-adopted/?shelter_id%5B0%5D=NY1257">Happy Tails - Already Adopted</a>
            </div>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="events">Events</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="https://www.facebook.com/Free-To-Be-Me-Rescue-890858194267665/">Facebook</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="contact">Contacts</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="http://paypal.me/FreeToBeMeRescue">Donate</a>
          </li>
          <li class="nav-item">
            <a class="nav-link text-white" href="stories">Stories</a>
          </li>
        </ul>
        <a class="ml-3 btn navbar-btn btn-primary" href="form.php">Apply Now</a>
      </div>
    </div>
  </nav>

  <div class="py-5 gradient-overlay" style="background-image: url(&quot;images/dog-foodprint.jpg&quot;);">
    <div class="container py-5">
      <div class="row">
        <div class="col-md-3 text-white">
          <img class="img-fluid d-block mx-auto mb-5" src="images/FTBMR_Logo No Background.png"> </div>
        <div class="col-md-9 text-white align-self-center">
          <h1 class="display-3 mb-4 text-center">Free To Be Me Rescue</h1>
          <p class="lead mb-5 text-center">It is our goal to never say no to a pet in need. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
            <br> </p>
          <a href="https://goo.gl/WzNm1Q" class="btn btn-primary mx-1 text-center btn-lg">Adopt your new friend today</a>
        </div>
      </div>
    </div>
  </div>
<div class="py-5 text-white bg-info">
    <div class="container" style="color: black;">
        <?php
    if (isset($sent_message)) {
        echo $sent_message;
    }
    $form->render();
    ?>
        
    </div>
  </div>
  <div class="py-5 bg-dark text-white">
    <div class="container">
      <div class="row">
        <div class="col-md-6 text-center align-self-center"><script src="//dashboard.time.ly/js/embed.js" data-src="https://events.time.ly/mgffi1e?view=agenda&notoolbar=1&range=events&events=3" data-max-height="232" id="timely_script" class="timely-script"></script></div>
        <div class="col-md-6 text-center align-self-center">
          <p class="mb-5"> <strong>Free To Be Me Rescue</strong>
            <br>154 Delaware Avenue
            <br>Delmar, NY 12054
            <br>501(c)(3) not for profit
            <br>
            <a href="mailto:FreeToBeMeRescue@gmail.com">FreeToBeMeRescue@gmail.com</a>
          </p>
          <div class="my-3 row">
            <div class="col-4 col-md-12">
              <a href="https://www.facebook.com/Free-To-Be-Me-Rescue-890858194267665/" target="_blank"><i class="fa fa-3x fa-facebook"></i></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.2.1.min.js" integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/js/bootstrap.min.js" integrity="sha384-h0AbiXch4ZDo7tp9hKZ4TsHbi047NrKGLO3SEJAg45jXxnGIfYzk4Si90RDIqNm1" crossorigin="anonymous"></script>
  <?php
    $form->printIncludes('js');
    $form->printJsCode();
  ?>
</body>

</html>