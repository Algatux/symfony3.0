<?php

namespace AppBundle\Entity;

use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Wallet
 *
 * @ORM\Table(name="wallet")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WalletRepository")
 */
class Wallet
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = 5,
     *      max = 255
     *     )
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Currency()
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private $currency;

    /**
     * @var WalletOperation[]
     *
     * @ORM\OneToMany(targetEntity="WalletOperation", mappedBy="wallet", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $operations;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="wallets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     */
    private $user;


    public function __construct()
    {
        $this->name = uniqid('wallet_');
        $this->amount = 0;
        $this->operations = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Wallet
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @param int $amount
     */
    public function setAmount(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * Set amount
     *
     * @param Money $amount
     *
     * @return Wallet
     */
    public function setMoneyAmount(Money $amount): Wallet
    {
        $this->amount = $amount->getAmount();
        $this->currency = $amount->getCurrency()->getCurrencyCode();

        return $this;
    }

    /**
     * Get amount
     *
     * @return Money
     */
    public function getMoneyAmount(): Money
    {
        return new Money($this->amount, new Currency($this->currency));
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;
    }

    /**
     * @return Currency
     */
    public function getCurrencyObject(): Currency
    {
        return new Currency($this->currency);
    }

    /**
     * @param Currency $currency
     */
    public function setCurrencyObject(Currency $currency)
    {
        $this->currency = $currency->__toString();
    }

    /**
     * @return WalletOperation[]
     */
    public function getOperations()
    {
        return $this->operations;
    }

    /**
     * @param WalletOperation[] $operations
     */
    public function setOperations(array $operations)
    {
        $this->operations = $operations;
    }

    /**
     * @param WalletOperation $operation
     */
    public function addOperation(WalletOperation $operation)
    {
        if (! $this->operations->contains($operation)) {
            $this->operations->add($operation);
        }
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

}

