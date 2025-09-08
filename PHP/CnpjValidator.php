<?php
class CnpjCalculator
{
    public static function validarCNPJ(string $cnpj): bool
    {
        $limpo = strtoupper(preg_replace('/[^A-Z0-9]/', '', $cnpj));
        if (strlen($limpo) !== 14)
            return false;

        $base12 = substr($limpo, 0, 12);
        $dv = substr($limpo, 12, 2);

        if (preg_match('/^[0-9]{12}$/', $base12)) {
            return self::calcularDvNumerico($base12, $dv);
        } elseif (preg_match('/^[0-9A-Z]{12}$/', $base12)) {
            return self::calcularDvAlfanumerico($base12, $dv);
        }
        return false;
    }

    private static function calcularDvNumerico(string $base12, string $dvInformado): bool
    {
        if (!preg_match('/^[0-9]{12}$/', $base12) || !preg_match('/^[0-9]{2}$/', $dvInformado))
            return false;

        $d1 = self::calcularUmDv($base12, false);
        $d2 = self::calcularUmDv($base12 . $d1, true);
        return $dvInformado === (string) $d1 . (string) $d2;
    }

    private static function calcularDvAlfanumerico(string $base12, string $dvInformado): bool
    {
        if (!preg_match('/^[0-9A-Z]{12}$/', $base12) || !preg_match('/^[0-9]{2}$/', $dvInformado))
            return false;

        $d1 = self::calcularUmDv($base12, false);
        $d2 = self::calcularUmDv($base12 . $d1, true);
        return $dvInformado === (string) $d1 . (string) $d2;
    }

    private static function calcularUmDv(string $texto, bool $comDv): int
    {
        $len = $comDv ? 13 : 12;
        $pesos = self::gerarPesos($len);
        $soma = 0;
        for ($i = 0; $i < $len; $i++) {
            $soma += self::valorChar($texto[$i]) * $pesos[$i];
        }
        $resto = $soma % 11;
        return ($resto == 0 || $resto == 1) ? 0 : 11 - $resto;
    }

    private static function valorChar(string $c): int
    {
        $o = ord($c);
        if (($o >= 48 && $o <= 57) || ($o >= 65 && $o <= 90)) {
            return $o - 48;
        }
        throw new InvalidArgumentException("Caractere inválido: $c");
    }

    private static function gerarPesos(int $len): array
    {
        $pesos = [];
        $w = 2;
        for ($i = $len - 1; $i >= 0; $i--) {
            $pesos[$i] = $w;
            $w++;
            if ($w > 9)
                $w = 2;
        }
        ksort($pesos);
        return $pesos;
    }
}