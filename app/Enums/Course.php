<?php

namespace App\Enums;

enum Course: string
{
    case ANALISE_DESENVOLVIMENTO_SISTEMAS = 'Análise e Desenvolvimento de Sistemas';
    case DESIGN_MIDIAS_DIGITAIS = 'Design de Mídias Digitais';
    case GESTAO_COMERCIAL = 'Gestão Comercial';
    case GESTAO_PRODUCAO_INDUSTRIAL = 'Gestão da Produção Industrial';
    case GESTAO_TECNOLOGIA_INFORMACAO = 'Gestão da Tecnologia da Informação';
    case GESTAO_EMPRESARIAL = 'Gestão Empresarial';
    case GESTAO_FINANCEIRA = 'Gestão Financeira';
    case LOGISTICA = 'Logística';
    case PROFESSOR = 'Professor';
    case ADMINISTRADOR = 'Administrador';
}
