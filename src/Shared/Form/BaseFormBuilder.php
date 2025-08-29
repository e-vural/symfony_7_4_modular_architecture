<?php

namespace App\Shared\Form;

use App\Shared\Exception\FormException;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BaseFormBuilder extends AbstractTypeExtension
{
    const EXCLUDE_FIELDS = "exclude_fields";
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {

    }


    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        //extra field hatası gelince gönderilen içerikte sorun var demektir. Dev ortamında istenen içeriği geri dönmeliyiz.

        $builder->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();


            $allow_exception = $form->getConfig()->getOption("allow_exception");
            $formErrors = $form->getErrors(true);

//            $expectedFields = [];
//            foreach ($form as $child) {
//                $expectedFields[] = $child->getName();
//            }
//            dump($form);

            if($formErrors->count()){

                $errors = [];
                $errorMessage  = null;
                /** @var FormError $formError */
                foreach ($formErrors as $formError) {
                    if(empty($errorMessage)){
                        $errorMessageTemplate =  $formError->getMessageTemplate();
                        $errorMessage = $formError->getMessage();

                        if("This form should not contain extra fields." === $errorMessageTemplate){
                            $errorMessage = "Gönderilen içerik hatalı.";
                        }
                    }
                    $errors[$formError->getOrigin()->getName()][] = $formError->getMessage();
                }
//                dd($errors);
//                dump($form->getName(),$allow_exception,$errors);
                if($allow_exception){
                    throw new FormException($errorMessage,$errors);
                }
            }else{
                $dataClass = $form->getConfig()->getDataClass();


                if(!empty($dataClass) && is_object($data)){
                    try {
                        $this->entityManager->getClassMetadata($dataClass);
                        $this->entityManager->persist($data);
                    }catch (\Exception $exception){

                    }

                }
            }
        },-1000);

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            dd($event);

            $excludeFields = $event->getForm()->getConfig()->getOption(self::EXCLUDE_FIELDS);

            if (!empty($excludeFields)) {
                foreach ($excludeFields as $excludeField) {
                    $event->getForm()->remove($excludeField);
                }
            }


        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefined(self::EXCLUDE_FIELDS);
        $resolver->setAllowedTypes(self::EXCLUDE_FIELDS, 'array');
        $resolver->setDefined('allow_exception');
        $resolver->setAllowedTypes('allow_exception', 'bool'); // sadece boolean türüne izin veriyoruz
        $resolver->setDefault("allow_exception",true);
        $resolver->setDefault("csrf_protection",false);
        $resolver->setDefault("csrf_message","Please try to resubmit the form.");
        parent::configureOptions($resolver);
    }

    public static function getExtendedTypes(): iterable
    {
//        return [FormType::class];
//        return [AbstractForm::class];
//        return [MyAbstractForm::class];
        return [BaseForm::class];
    }
}
