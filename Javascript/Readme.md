# CnpjCalculator

Uma biblioteca em **Javascript** para validação de **CNPJs tradicionais (numéricos)** e **CNPJs alfanuméricos** (padrão SERPRO).

---

## Funcionalidades

- Valida **CNPJ clássico** (somente números)  
- Valida **CNPJ alfanumérico** (letras + números, ex: `E9.640.DP2/MQ95-33`)  
- Aceita CNPJs **com ou sem máscara**  
- Detecta automaticamente se o CNPJ é numérico ou alfanumérico  
- Cálculo dos dígitos verificadores (DV) conforme especificação oficial (módulo 11)

---

## Uso

```javascript
console.log(CnpjCalculator.validarCNPJ("11.444.777/0001-61")); // true
console.log(CnpjCalculator.validarCNPJ("11444777000161")); // true
console.log(CnpjCalculator.validarCNPJ("E9.640.DP2/MQ95-33")); // true
console.log(CnpjCalculator.validarCNPJ("E9640DP2MQ9533")); // true

console.log(CnpjCalculator.validarCNPJ("11.444.777/0001-62")); // false
console.log(CnpjCalculator.validarCNPJ("E9.640.DP2/MQ95-34")); // false
console.log(CnpjCalculator.validarCNPJ("123")); // false