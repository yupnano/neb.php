<?php
/**
 * Created by PhpStorm.
 * User: yupna
 * Date: 2018/5/23
 * Time: 16:06
 */

namespace Nebulas\Rpc;

use Nebulas\Rpc\HttpProvider;
use Nebulas\Rpc\Neb;
use Nebulas\Utils\Utils;

class Api
{
    private $provider;
    private $path;
    private $apiVersion;


    function __construct(Neb $neb, $apiVersion)
    {
        $this->setProvidr($neb->provider);
        $this->apiVersion = $apiVersion;
    }

    public function setProvidr($provider){
        $this->provider = $provider;
        $this->path = "/user";
    }

    /**
     * Get state of Nebulas Network.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getnebstate}
     *
     * @return mixed
     */
    public function getNebState(){
        $param = array();
        return $this->sendRequest("get", "/nebstate", $param);
    }

    /**
     * Get latest irreversible block of Nebulas Network.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#latestirreversibleblock}
     *
     * @return mixed
     */
    public function latestIrreversibleBlock(){
        $param = array();
        return $this->sendRequest("get", "/lib", $param);
    }

    /**
     * Method return the state of the account. Balance and nonce.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getaccountstate}
     *
     * @param string $address
     * @param int $height   int value, the height at which you want to get the account state
     * @return mixed
     */
    public function getAccountState(string $address,int $height = 0){
        $param = array(
            "address" => $address,
            "height" => $height
        );
        return $this->sendRequest("post", "/accountstate", $param);
    }

    /**
     * Simulate a transaction, to get the result and error info.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#call}
     *
     * @param string $from
     * @param string $to
     * @param string $value note that it's unit is wei.
     * @param int $nonce
     * @param string $gasprice
     * @param string $gasLimit
     * @param string|null $type -transaction type, should be "binary","deploy", or "call", or null
     * @param array|null $contract -contract data for deploy/call type. Please refer to {@link https://github.com/nebulasio/wiki/blob/master/rpc_admin.md/#sendtransaction}
     * @param string|null $binary
     * @return mixed
     */
    public function call(string $from, string $to,
                         string $value, int $nonce,
                         string $gasprice, string $gasLimit,
                         string $type = null, array $contract = null, string $binary = null){
        $param = array(
            "from" => $from,
            "to" => $to,
            "value" => $value,
            "nonce" => $nonce,
            "gasPrice" => $gasprice,
            "gasLimit" => $gasLimit,
            "contract" => $contract,
            "type" => $type,
            "binary" => $binary
        );
        return $this->sendRequest("post", "/call", $param);
    }

    /**
     * Send a signed transaction data. The data is a base64 encoded string of transaction details.
     * The data could be generated by <code>hashTransaction and then signTransaction </code>.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#sendrawtransaction}
     *
     * @param string $data
     * @return mixed
     */
    public function sendRawTransaction(string $data){
        $param = array(
            "data" => $data
        );
        return $this->sendRequest("post", "/rawtransaction", $param);
    }

    /**
     * Get block header info by the block hash.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getblockbyhash}
     *
     * @param string $hash
     * @param bool $isFull
     * @return mixed
     */
    public function getBlockByHash(string $hash, bool $isFull = false){
        $param = array(
            "hash" => $hash,
            "full_fill_transaction" => $isFull, // json_encode($isFull)
        );
        return $this->sendRequest("post", "/getBlockByHash", $param);
    }

    /**
     * Get block header info by the block height.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getblockbyheight}
     *
     * @param string $height
     * @return mixed
     */
    public function getBlockByHeight(string $height){
        $param = array(
            "height" => $height,
        );
        return $this->sendRequest("post", "/getBlockByHeight", $param);
    }

    /**
     * Get transactionReceipt info by tansaction hash.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#gettransactionreceipt}
     *
     * @param string $hash
     * @return mixed
     */
    public function getTransactionReceipt(string $hash){
        $param = array(
            "hash" => $hash,
        );
        return $this->sendRequest("post", "/getTransactionReceipt", $param);
    }

    /**
     * Get transactionReceipt info by contract address.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#gettransactionbycontract}
     *
     * @param string $address
     * @return mixed
     */
    public function getTransactionByContract(string $address){
        $param = array(
            "address" => $address,
        );
        return $this->sendRequest("post", "/getTransactionByContract", $param);

    }

    /**
     * Return the subscribed events of transaction & block.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#subscribe}
     *
     * @param string $topics
     * @param string $onDownloadProgress
     */
    public function subscribe(string $topics, string $onDownloadProgress){

    }

    /**
     * Get current gasPrice.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getgasprice}
     *
     * @return mixed
     */
    public function gasPrice(){
        $param = array();
        return $this->sendRequest("get", "/getGasPrice", $param);
    }

    /**
     * it's parameter is the same with {@code call}.
     *
     */
    public function estimateGas(string $from, string $to,
                                string $value, $nonce,      //todo $nonce int or string
                                $gasPrice, $gasLimit,
                                string $type = null, array $contract = null, string $binary = null)
    {
        $param = array(
            "from" => $from,
            "to" => $to,
            "value" => $value,
            "nonce" => $nonce,
            "gasPrice" => $gasPrice,
            "gasLimit" => $gasLimit,
            "type" => $type,
            "contract" => $contract,
            "binary" => $binary,        //todo: check type
        );
        return $this->sendRequest("post", "/estimateGas", $param);
    }

    /**
     * Return the events list of a given transaction.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#geteventsbyhash}
     *
     * @param string $hash
     * @return mixed
     */
    public function getEventsByHash(string $hash){
        $param = array("hash" => $hash);
        return $this->sendRequest("post", "/getEventsByHash", $param);
    }

    /**
     * Get the current dpos dynasty.
     * @see {@link https://github.com/nebulasio/wiki/blob/master/rpc.md#getdynasty}
     *
     * @param string $height
     * @return mixed
     */
    public function getDynasty(string $height){
        $param = array("height" => $height);
        return $this->sendRequest("post", "/dynasty", $param);
    }

    function sendRequest(string $method, string $api, array $param){
        $api = $this->path . $api;       // e.g. "/user/accountstate"
        //$param = json_encode($param);       // e.g. "{"address":"n1H2Yb5Q6ZfKvs61htVSV4b1U2gr2GA9vo6","height":"0"}"
        $param = Utils::JsonEncode($param);
        //echo "payload: ", $param,PHP_EOL;

        $options = (object) array(
            "method" => $method,
        );
        return $this->provider->request($api, $param, $this->apiVersion, $options);
    }


}