<?php

namespace App\Form;
use App\Entity\Category;
use App\Entity\Post;
use App\Entity\Protagonist;
use App\Entity\Topic;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


/**
 * Class PostType
 * @package App\Form
 */
class PostType extends AbstractType
{
    /** @var TokenStorageInterface $tokenStorage */
    protected $tokenStorage;

    /** @var User $currentUser */
    protected $currentUser;

    /**
     * PostType constructor.
     * @param TokenStorageInterface $tokenStorage
     */
    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->currentUser = $tokenStorage->getToken()->getUser();
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            // Topic
            ->add('topic', EntityType::class, [
                'class' => Topic::class,
                'label' => false,
            ])

            // Protagonist
            ->add('protagonist', EntityType::class, [
                'class' => Protagonist::class,
                'label' => false,
                'query_builder' => function (EntityRepository $er): QueryBuilder {
                    $qb = $er->createQueryBuilder('protagonist');
                    $qb->where('protagonist.user = :user');
                    $qb->setParameter('user', $this->currentUser);

                    return $qb;
                }
            ])

            // Content
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Corps du post',
                ]
            ])

            // PRE_SET_DATA -> Removing topic field if already set
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $parent = $form->getParent();
                if (($parent !== null && $parent->getData() instanceof Topic)
                    || $event->getData()->getTopic() instanceof Topic) {
                    $form->remove('topic');
                }
            })

            // PRE_SET_DATA -> Removing protagonist field if not RP category
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                /** @var Post $post */
                $post = $event->getData();
                $topic = $post ? $post->getTopic() : $event->getForm()->getParent()->getData();
                if ($topic !== null) {
                    if ($topic->getBoard()->getCategory()->getType() !== Category::TYPE_RP) {
                        $event->getForm()->remove('protagonist');
                    }
                }
            })

            // PRE_SUBMIT -> Setting current user to post
            ->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
                /** @var Post $post */
                $post = $event->getForm()->getData();
                if ($post) {
                    $post->setUser($this->currentUser);
                }
            })

        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Post::class,
        ]);
    }
}
