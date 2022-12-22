<?php

/**
 * MIT License
 *
 * Copyright (c) 2019 João M F Rebelo
 */
declare(strict_types=1);

namespace Rebelo\ATWs\StockMovement;

use Rebelo\ATWs\AATWs;
use Rebelo\ATWs\ATWsException;
use Rebelo\Date\Date;

/**
 * StockMovement Ws
 *
 * @author João Rebelo
 * @since  1.0.0
 */
class Ws extends AATWs implements IWs
{

    /**
     * The WSDL file path
     * @since 1.0.0
     */
    const WSDL = __DIR__ . DIRECTORY_SEPARATOR . "documentosTransporte.wsdl";

    /**
     *
     * @var \Logger
     * @since 1.0.0
     */
    protected \Logger $log;

    /**
     * @var \Rebelo\ATWs\StockMovement\StockMovement|\Rebelo\ATWs\StockMovement\PriorAgriculturalStockMovement|\Rebelo\ATWs\StockMovement\SubsequentAgriculturalStockMovement
     * @since 1.0.0
     */
    protected StockMovement|PriorAgriculturalStockMovement|SubsequentAgriculturalStockMovement $stockMovement;

    /**
     * Build the document request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws ATWsException
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    protected function buildBody(\XMLWriter $xml): void
    {
        $xml->startElementNs(AATWs::NS_ENVELOPE, "Body", null);

        if ($this->stockMovement instanceof StockMovement) {
            $this->buildBodyStockMovement($xml);
        } elseif ($this->stockMovement instanceof PriorAgriculturalStockMovement) {
            $this->buildBodyPriorAgricultural($xml);
        } elseif ($this->stockMovement instanceof SubsequentAgriculturalStockMovement) {
            $this->buildBodySubsequentAgricultural($xml);
        } else {
            throw new ATWsException(
                \sprintf(
                    "Unknown action for '%s'",
                    \get_class($this->stockMovement)
                )
            );
        }
        $xml->endElement(); //Body
    }

    /**
     * Build the Stock Movement request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodyStockMovement(\XMLWriter $xml): void
    {
        /** @var \Rebelo\ATWs\StockMovement\StockMovement $doc */
        $doc = $this->stockMovement;
        $xml->startElementNs(
            "ns0",
            "envioDocumentoTransporteRequestElem",
            "https://servicos.portaldasfinancas.gov.pt/sgdtws/documentosTransporte/"
        );

        $xml->writeElement(
            "TaxRegistrationNumber",
            $doc->getTaxRegistrationNumber()
        );

        $xml->writeElement("CompanyName", $doc->getCompanyName());

        $xml->startElement("CompanyAddress");
        $doc->getCompanyAddress()->buildXml($xml);
        $xml->endElement(); //CompanyAddress

        $xml->writeElement("DocumentNumber", $doc->getDocumentNumber());

        $xml->writeElement(
            "ATCUD",
            $doc->getTaxRegistrationNumber()
        );

        if ($doc->getATDocCodeID() !== null) {
            $xml->writeElement("ATDocCodeID", $doc->getATDocCodeID());
        }

        $xml->writeElement("MovementStatus", $doc->getMovementStatus());

        $xml->writeElement(
            "MovementDate",
            $doc->getMovementDate()->format(Date::SQL_DATE)
        );

        $xml->writeElement("MovementType", $doc->getMovementType());

        if ($doc->getCustomerTaxID() !== null) {
            $xml->writeElement("CustomerTaxID", $doc->getCustomerTaxID());
        }

        if ($doc->getSupplierTaxID() !== null) {
            $xml->writeElement("SupplierTaxID", $doc->getSupplierTaxID());
        }

        if ($doc->getCustomerAddress() !== null) {
            $xml->startElement("CustomerAddress");
            $doc->getCustomerAddress()->buildXml($xml);
            $xml->endElement(); //CustomerAddress
        }

        if ($doc->getAddressTo() !== null) {
            $xml->startElement("AddressTo");
            $doc->getAddressTo()->buildXml($xml);
            $xml->endElement(); //AddressTo
        }

        $xml->startElement("AddressFrom");
        $doc->getAddressFrom()->buildXml($xml);
        $xml->endElement(); //AddressFrom

        if ($doc->getMovementEndTime() !== null) {
            $xml->writeElement(
                "MovementEndTime",
                $doc->getMovementEndTime()->format(Date::DATE_T_TIME)
            );
        }

        $xml->writeElement(
            "MovementStartTime",
            $doc->getMovementStartTime()->format(Date::DATE_T_TIME)
        );

        if ($doc->getVehicleID() !== null) {
            $xml->writeElement("VehicleID", $doc->getVehicleID());
        }

        foreach ($doc->getLines() as $line) {
            $xml->startElement("Line");
            $line->buildXml($xml);
            $xml->endElement(); //Line
        }

        $xml->endElement(); //envioDocumentoTransporteRequestElem
    }

    /**
     * Build the Prior Agricultural request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodyPriorAgricultural(\XMLWriter $xml): void
    {
        /** @var \Rebelo\ATWs\StockMovement\PriorAgriculturalStockMovement $doc */
        $doc = $this->stockMovement;
        $xml->startElementNs(
            "ns0",
            "registerFarmersPurchaseDocRequestElem",
            "https://servicos.portaldasfinancas.gov.pt/sgdtws/GuiasAquisicaoProdAgricola/"
        );
        $xml->writeElement(
            "TaxRegistrationNumber",
            $doc->getTaxRegistrationNumber()
        );

        $xml->writeElement("CompanyName", $doc->getCompanyName());

        $xml->startElement("CompanyAddress");
        $doc->getCompanyAddress()->buildXml($xml);
        $xml->endElement(); //CompanyAddress

        $xml->writeElement("DocumentNumber", $doc->getDocumentNumber());

        if ($doc->getATDocCodeID() !== null) {
            $xml->writeElement("ATDocCodeID", $doc->getATDocCodeID());
        }

        $xml->writeElement(
            "ATCUD",
            $doc->getTaxRegistrationNumber()
        );

        $xml->writeElement("MovementStatus", $doc->getMovementStatus());

        $xml->writeElement(
            "MovementDate",
            $doc->getMovementDate()->format(Date::SQL_DATE)
        );

        $xml->writeElement("MovementType", $doc->getMovementType());

        $xml->writeElement(
            "MovementStartTime",
            $doc->getMovementStartTime()->format(Date::SQL_DATE)
        );

        $xml->writeElement(
            "InAcores",
            $doc->getInAzores() ? "true" : "false"
        );

        $stack = \is_array($doc->getFarmerTaxID()) ?
            $doc->getFarmerTaxID() : [$doc->getFarmerTaxID()];
        foreach ($stack as $farmerTaxID) {
            $xml->writeElement(
                "FarmerTaxID", $farmerTaxID
            );
        }

        $xml->endElement(); //registerFarmersPurchaseDocRequestElem
    }

    /**
     * Build the Subsequent Agricultural request xml body
     * @param \XMLWriter $xml
     * @return void
     * @throws \Rebelo\Date\DateFormatException
     * @since 1.0.0
     */
    public function buildBodySubsequentAgricultural(\XMLWriter $xml): void
    {
        /** @var \Rebelo\ATWs\StockMovement\SubsequentAgriculturalStockMovement $doc */
        $doc = $this->stockMovement;
        $xml->startElementNs(
            "ns0",
            "registerFarmersPurchasePartialDocRequestElem",
            "https://servicos.portaldasfinancas.gov.pt/sgdtws/GuiasAquisicaoProdAgricola/"
        );
        $xml->writeElement(
            "TaxRegistrationNumber",
            $doc->getTaxRegistrationNumber()
        );

        $xml->writeElement("CompanyName", $doc->getCompanyName());

        $xml->startElement("CompanyAddress");
        $doc->getCompanyAddress()->buildXml($xml);
        $xml->endElement(); //CompanyAddress

        $xml->writeElement("DocumentNumber", $doc->getDocumentNumber());

        $xml->writeElement(
            "ATCUD",
            $doc->getTaxRegistrationNumber()
        );

        $xml->writeElement("MovementStatus", $doc->getMovementStatus());

        $xml->writeElement(
            "MovementDate",
            $doc->getMovementDate()->format(Date::SQL_DATE)
        );

        $xml->writeElement("MovementType", $doc->getMovementType());

        $xml->writeElement("FarmerTaxID", $doc->getFarmerTaxID());

        if ($doc->getAddressTo() !== null) {
            $xml->startElement("AddressTo");
            $doc->getAddressTo()->buildXml($xml);
            $xml->endElement(); //AddressTo
        }

        $xml->startElement("AddressFrom");
        $doc->getAddressFrom()->buildXml($xml);
        $xml->endElement(); //AddressFrom

        if ($doc->getMovementEndTime() !== null) {
            $xml->writeElement(
                "MovementEndTime",
                $doc->getMovementEndTime()->format(Date::DATE_T_TIME)
            );
        }

        $xml->writeElement(
            "MovementStartTime",
            $doc->getMovementStartTime()->format(Date::DATE_T_TIME)
        );

        if ($doc->getVehicleID() !== null) {
            $xml->writeElement("VehicleId", $doc->getVehicleID());
        }

        if ($doc->getOrderReferences() !== null) {
            $xml->startElement("OrderReference");
            $xml->writeElement(
                "OriginatingON", $doc->getOrderReferences()->getOriginatingOn()
            );
            $xml->writeElement(
                "MovementType", $doc->getOrderReferences()->getMovementType()
            );
            $xml->endElement(); //OrderReferences
        }

        foreach ($doc->getLines() as $line) {
            $xml->startElement("Line");
            $line->buildXml($xml);
            $xml->endElement(); //Line
        }

        $xml->endElement(); //registerFarmersPurchasePartialDocRequestElem
    }

    /**
     * Submit the stock movement
     * @param \Rebelo\ATWs\StockMovement\StockMovement|\Rebelo\ATWs\StockMovement\PriorAgriculturalStockMovement|\Rebelo\ATWs\StockMovement\SubsequentAgriculturalStockMovement $stockMovement
     * @return \Rebelo\ATWs\StockMovement\Response
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function submit(StockMovement|PriorAgriculturalStockMovement|SubsequentAgriculturalStockMovement $stockMovement): Response
    {
        $this->stockMovement = $stockMovement;
        return Response::factory(
            parent::doRequest()
        );
    }

    /**
     * Get the StockMovement webservice WSDL URI
     * @return string
     * @throws \Rebelo\ATWs\ATWsException
     * @since 1.0.0
     */
    public function getWsdl(): string
    {
        if (\file_exists(static::WSDL) === false) {
            throw new ATWsException("WSDL file not exist: " . static::WSDL);
        }
        return "file://" . static::WSDL;
    }

    /**
     * Get the Webservice action
     * @return string
     * @since        1.0.0
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function getWsAction(): string
    {
        if ($this->stockMovement instanceof StockMovement) {
            return "envioDocumentoTransporte";
        }

        if ($this->stockMovement instanceof PriorAgriculturalStockMovement) {
            return "registerFarmersPurchaseDoc";
        }

        if ($this->stockMovement instanceof SubsequentAgriculturalStockMovement) {
            return "registerFarmersPurchasePartialDoc";
        }
    }

    /**
     * Get the Webservice location
     * @return string
     * @since        1.0.0
     * @noinspection PhpInconsistentReturnPointsInspection
     */
    public function getWsLocation(): string
    {
        if ($this->stockMovement instanceof StockMovement) {
            return $this->isTest ?
                "https://servicos.portaldasfinancas.gov.pt:701/sgdtws/documentosTransporte" :
                "https://servicos.portaldasfinancas.gov.pt:401/sgdtws/documentosTransporte";
        }

        if ($this->stockMovement instanceof PriorAgriculturalStockMovement) {
            return $this->isTest ?
                "https://servicos.portaldasfinancas.gov.pt:702/sgdtws/GuiasAquisicaoProdAgricola" :
                "https://servicos.portaldasfinancas.gov.pt:402/sgdtws/GuiasAquisicaoProdAgricola";
        }

        if ($this->stockMovement instanceof SubsequentAgriculturalStockMovement) {
            return $this->isTest ?
                "https://servicos.portaldasfinancas.gov.pt:702/sgdtws/GuiasAquisicaoProdAgricola" :
                "https://servicos.portaldasfinancas.gov.pt:402/sgdtws/GuiasAquisicaoProdAgricola";
        }
    }

}
