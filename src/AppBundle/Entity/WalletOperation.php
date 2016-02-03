<?php

namespace AppBundle\Entity;

use AppBundle\Exceptions\WalletOperationNotSupportedException;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use SebastianBergmann\Money\Currency;
use SebastianBergmann\Money\Money;

/**
 * WalletOperation
 *
 * @ORM\Table(name="wallet_operation")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WalletOperationRepository")
 */
class WalletOperation implements \JsonSerializable
{

    const OPERATION_DEPOSIT = 'deposit';
    const OPERATION_WITHDRAW = 'withdraw';

    protected static $availableOperations = [
        self::OPERATION_DEPOSIT,
        self::OPERATION_WITHDRAW
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual(
     *     value = 0
     * )
     * @ORM\Column(name="amount", type="integer", nullable=false)
     */
    private $amount;

    /**
     * @var int
     *
     * @Assert\NotBlank()
     * @Assert\Currency()
     * @ORM\Column(name="currency", type="string", length=3, nullable=false)
     */
    private $currency;

    /**
     * @var string
     *
     * @Assert\NotBlank()
     * @Assert\Choice(
     *     choices = {"deposit", "withdraw"}, message = "Choose deposit or withdraw."
     * )
     * @ORM\Column(name="operation", type="string", length=255)
     */
    private $operation;

    /**
     * @var Wallet
     *
     * @ORM\ManyToOne(targetEntity="Wallet", inversedBy="operations")
     * @ORM\JoinColumn(name="wallet_id", referencedColumnName="id", nullable=false)
     */
    private $wallet;


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
     * Set amount
     *
     * @param Money $amount
     * @return WalletOperation
     */
    public function setMoneyAmount(Money $amount): WalletOperation
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
        $this->currency = $currency->getCurrencyCode();
    }

    /**
     * Set operation
     *
     * @param string $operation
     *
     * @return WalletOperation
     * @throws WalletOperationNotSupportedException
     */
    public function setOperation(string $operation): WalletOperation
    {

        if (!in_array($operation, self::$availableOperations)) {
            throw new WalletOperationNotSupportedException($operation);
        }

        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation
     *
     * @return string
     */
    public function getOperation(): string
    {
        return $this->operation;
    }

    /**
     * @return Wallet
     */
    public function getWallet(): Wallet
    {
        return $this->wallet;
    }

    /**
     * @param Wallet $wallet
     */
    public function setWallet(Wallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        return [
            "id" => $this->getId(),
            "wallet" => $this->wallet->getId(),
            "operation" => $this->getOperation(),
            "amount" => (float) $this->getMoneyAmount()->getConvertedAmount(),
            "currency" => $this->getCurrency()
        ];
    }

}
