To convert the AT certificate pfx to pem:
openssl pkcs12 -in filename.pfx -out cert.pem -nodes

Certificate password: TESTEwebservice
Last test certificate download: https://info.portaldasfinancas.gov.pt/pt/apoio_contribuinte/Faturacao/Documents/Certificado_testes.zip


In some version of openssl to convert the certificate without errors you need to change/add this configuration in openssl.cnf:

[provider_sect]
default = default_sect
legacy = legacy_sect
 
[default_sect]
activate = 1

[legacy_sect]
activate = 1
