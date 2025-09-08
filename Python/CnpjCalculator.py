import re

class CnpjCalculator:
    @staticmethod
    def validar_cnpj(cnpj: str) -> bool:
        limpo = re.sub(r'[^A-Z0-9]', '', cnpj.upper())
        if len(limpo) != 14:
            return False

        base12, dv = limpo[:12], limpo[12:]

        if re.fullmatch(r"\d{12}", base12):
            return CnpjCalculator._calcular_dv_numerico(base12, dv)
        elif re.fullmatch(r"[0-9A-Z]{12}", base12):
            return CnpjCalcul._calcular_dv_alfanumerico(base12, dv)
        return False

    @staticmethod
    def _calcular_dv_numerico(base12: str, dv: str) -> bool:
        if not re.fullmatch(r"\d{12}", base12) or not re.fullmatch(r"\d{2}", dv):
            return False
        d1 = CnpjCalculator._calcular_um_dv(base12, False)
        d2 = CnpjCalculator._calcular_um_dv(base12 + str(d1), True)
        return dv == f"{d1}{d2}"

    @staticmethod
    def _calcular_dv_alfanumerico(base12: str, dv: str) -> bool:
        if not re.fullmatch(r"[0-9A-Z]{12}", base12) or not re.fullmatch(r"\d{2}", dv):
            return False
        d1 = CnpjCalculator._calcular_um_dv(base12, False)
        d2 = CnpjCalculator._calcular_um_dv(base12 + str(d1), True)
        return dv == f"{d1}{d2}"

    @staticmethod
    def _calcular_um_dv(texto: str, com_dv: bool) -> int:
        length = 13 if com_dv else 12
        pesos = CnpjCalculator._gerar_pesos(length)
        soma = sum(CnpjCalculator._valor_char(texto[i]) * pesos[i] for i in range(length))
        resto = soma % 11
        return 0 if resto in (0, 1) else 11 - resto

    @staticmethod
    def _valor_char(c: str) -> int:
        o = ord(c)
        if (48 <= o <= 57) or (65 <= o <= 90):
            return o - 48
        raise ValueError(f"Caractere inválido: {c}")

    @staticmethod
    def _gerar_pesos(length: int) -> list[int]:
        pesos = [0] * length
        w = 2
        for i in range(length - 1, -1, -1):
            pesos[i] = w
            w += 1
            if w > 9:
                w = 2
        return pesos




