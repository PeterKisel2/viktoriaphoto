<?php

declare(strict_types=1);

namespace App\Presenters;

// use App\Email\RegistrationFormEmail;
use Nette\Application\UI\Form;
use Nette\Utils\ArrayHash;
use Nette\Utils\Html;
// use ShopUp\Emailer\EmailerAccessor;

final class EventPresenter extends BasePresenter
{

    public function actionDefault() : void
    {
        // $this->template->events = $this->events->getEvents( 3 );
    }
    
    public function actionDetail( string $id ) : void
    {
        // $this->template->event = $this->events->getEventById( $id );
    }

    public function renderRezervace( string $id ) : void
    {
        // $this->template->event = $this->events->getEventById( $id );

        if( !isset( $_SESSION['participants'] ) ) $_SESSION['participants'] = 1;

        $this->template->participants = $_SESSION['participants'];
    }

    // public function handleSetActive()
    // {
    //     if ( $this->isAjax() )
    //     {
            
    //         $perPants = $this->getRequest()->getPost();

    //         \TRacy\Debugger::barDump($perPants);
    //         $_SESSION['participants'] = $perPants['parts'];

    //         $this->template->participants = $perPants['parts'];
    //         // $this->payload->message = 'Success';

    //         $this->redrawControl();
    //         // $this->redrawControl('reservation');

    //         // $this->sendPayload();
    //     }
    // }


    // protected function createComponentParticipant():Form
    // {
    //     $form = new Form;
        
    //     $participant = $form->addInteger('pocet_ucastniku', "Počet účastníků události")
    //         ->setDefaultValue( @$_SESSION['participants']?:1 )
    //         ->addRule($form::RANGE, 'Úroveň musí být v rozsahu mezi %d a %d.', [1, 10])
    //         ->setRequired( "%label je povinne" );
    
    //     $participant->addRule(Form::MIN_LENGTH, 'Number must be at least %d', 1);

    //     $form->onSuccess[] = function( Form $form, ArrayHash $values )
    //     {
    //         $_SESSION['participants'] = $values->pocet_ucastniku;

    //         $this->template->participants = $values->pocet_ucastniku;

    //         $this->redrawControl();
    //     };

    //     return $form;
    // }
    
    protected function createComponentRegistrationForm() : Form
    {
        $form = new Form();

        $participant = $form->addInteger('pocet_ucastniku', "Počet účastníků události")
            ->setDefaultValue( @$_SESSION['participants']?:1 )
            ->addRule($form::RANGE, 'Úroveň musí být v rozsahu mezi %d a %d.', [1, 10])
            ->setRequired( "%label je povinne" );
    
        $participant->addRule(Form::MIN_LENGTH, 'Number must be at least %d', 1);



        // inputs
        $name = $form->addText('jmeno', 'Jméno');
        $surname = $form->addText('prijmeni', 'Příjmení');
        $email = $form->addEmail('email', 'E-mail');
        $tel = $form->addText('mobil', "Mobilní číslo")->setHtmlType('tel');
        $birth_date = $form->addText('datum_narozeni', 'Datum narození');

        $street = $form->addText('ulice', 'Ulice a číslo popisné');
        $city = $form->addText('mesto', 'Město');
        $zip = $form->addText('psc', "PSČ");

        // helpers
        $name->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. Jan"));
        $surname->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. Novák"));
        $email->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. jan.novak@email.cz"));
        $tel->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. +420 123456789"));
        $street->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. Revoluční 6"));
        $city->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. Praha"));
        $zip->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. 110 00"));
        $birth_date->setOption( 'desc', Html::el('small')
            ->addAttributes(['class' => 'small mutted'])
            ->setHtml("Např. 18.09.2002"));

        $form->addSubmit('submit', 'Odeslat');

        $form->onSubmit[] = function( Form $form )
        {
            if($this->isAjax()){
                $values = $form->getValues();
                \TRacy\Debugger::barDump($values);
                $_SESSION['participants'] = $values->pocet_ucastniku;
    
                $this->template->participants = $values->pocet_ucastniku;
    
                $this->redrawControl();
            }
            
        };

        $form->onSuccess[] = [$this, 'registrationFormSucceeded'];
        
        return $form;
    }

    public function registrationFormSucceeded( Form $form, ArrayHash $values ) : void
    {

        \TRacy\Debugger::barDump($values);
        $participants = $form->getHttpData($form::DATA_TEXT, "participants[]");


        // $this->emailerAccessor->get()->getEmailServiceByType(RegistrationFormEmail::class, [
        //     'to' => 'test@me.com',
        //     'subject' => 'Event | Registrace ucastniku',
        //     'jmeno' => $values->jmeno,
        //     'prijmeni' => $values->prijmeni,
        //     'email' => $values->email,
        //     'mobil' => $values->mobil,
        //     'datum_narozeni' => $values->datum_narozeni,
        //     'ulice' => $values->ulice,
        //     'mesto' => $values->mesto,
        //     'psc' => $values->psc,
        //     'pocet_ucastniku' => $values->pocet_ucastniku,
        //     'participants' => $participants
        // ])->send();

        $_SESSION['participants'] = 1;

        // $form->setValues([]);
        // $this->redrawControl('wrapper');
        // $form->reset();
    }

    
}
