<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Informe INACAP - {{ $caso->rut_estudiante }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 11px; color: #333; line-height: 1.4; }
        
        /* Encabezado */
        .header { margin-bottom: 20px; border-bottom: 3px solid #D71920; padding-bottom: 10px; }
        .title { font-size: 16px; font-weight: bold; color: #D71920; margin: 0; text-transform: uppercase; text-align: center; }
        .subtitle { font-size: 12px; font-weight: bold; color: #555; margin-top: 5px; text-align: center; }
        
        /* Secciones */
        .section { margin-bottom: 25px; }
        .section-title { 
            font-size: 12px; font-weight: bold; color: #fff; background-color: #333; 
            padding: 5px 10px; margin-bottom: 10px; text-transform: uppercase;
        }

        /* Tablas */
        table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        th { background-color: #f2f2f2; font-weight: bold; text-align: left; padding: 6px; border: 1px solid #ccc; width: 25%; }
        td { padding: 6px; border: 1px solid #ccc; text-align: left; vertical-align: top; }
        
        .long-text { text-align: justify; white-space: pre-line; }
        
        /* Estados de Validación */
        .status-accepted { color: green; font-weight: bold; text-transform: uppercase; }
        .status-rejected { color: #D71920; font-weight: bold; text-transform: uppercase; }
        .status-pending { color: #666; font-style: italic; }

        .footer { 
            position: fixed; bottom: 0; left: 0; right: 0; 
            font-size: 9px; text-align: center; color: #999; 
            border-top: 1px solid #eee; padding-top: 10px; 
        }
    </style>
</head>
<body>

    {{-- 1. ENCABEZADO --}}
    <div class="header">
        <div class="title">Informe de Ajustes Razonables</div>
        <div class="subtitle">Unidad de Apoyo Pedagógico - Dirección de Asuntos Estudiantiles</div>
    </div>

    {{-- 2. ANTECEDENTES --}}
    <div class="section">
        <div class="section-title">1. Antecedentes del Estudiante</div>
        <table>
            <tr>
                <th>Nombre Completo</th><td>{{ $caso->nombre_estudiante }}</td>
                <th>RUT</th><td>{{ $caso->rut_estudiante }}</td>
            </tr>
            <tr>
                <th>Carrera</th><td>{{ $caso->carrera }}</td>
                <th>Vía de Ingreso</th><td>{{ $caso->via_ingreso ?? 'No especificada' }}</td>
            </tr>
            <tr>
                <th>Fecha Entrevista</th><td>{{ $caso->created_at->format('d/m/Y') }}</td>
                <th>ID Caso</th><td>#{{ $caso->id }}</td>
            </tr>
        </table>
    </div>

    {{-- 3. SALUD Y DISCAPACIDAD --}}
    <div class="section">
        <div class="section-title">2. Antecedentes de Salud y Discapacidad</div>
        <table>
            <tr>
                <th>Tipo de Discapacidad</th>
                <td colspan="3">
                    @if(is_array($caso->tipo_discapacidad)) 
                        {{ implode(', ', $caso->tipo_discapacidad) }}
                    @else 
                        {{ $caso->tipo_discapacidad ?? 'No especificado' }}
                    @endif
                </td>
            </tr>
            <tr>
                <th>Origen</th>
                <td>{{ $caso->origen_discapacidad ?? '-' }}</td>
                <th>Documentación</th>
                <td>
                    {{ $caso->credencial_rnd ? 'RND, ' : '' }}
                    {{ $caso->pension_invalidez ? 'Pensión Invalidez, ' : '' }}
                    {{ $caso->certificado_medico ? 'Certificado Médico' : '' }}
                </td>
            </tr>
            <tr>
                <th>Tratamiento Farmacológico</th>
                <td colspan="3">{{ $caso->tratamiento_farmacologico ?? 'No reporta.' }}</td>
            </tr>
            <tr>
                <th>Apoyos Externos</th>
                <td colspan="3">
                    <strong>Especialista:</strong> {{ $caso->acompanamiento_especialista ?? 'No' }} <br>
                    <strong>Redes:</strong> {{ $caso->redes_apoyo ?? 'No' }}
                </td>
            </tr>
        </table>
    </div>

    {{-- 4. ANTECEDENTES ACADÉMICOS --}}
    <div class="section">
        <div class="section-title">3. Antecedentes Académicos Previos</div>
        <table>
            <tr>
                <th>Enseñanza Media</th>
                <td>{{ $caso->enseñanza_media_modalidad ?? 'No especificado' }}</td>
                <th>Estudios Superiores</th>
                <td>
                    @if($caso->estudio_previo_superior)
                        Sí ({{ $caso->nombre_institucion_anterior }} - {{ $caso->carrera_anterior }})
                    @else No @endif
                </td>
            </tr>
            <tr>
                <th>Programa de Integración (PIE)</th>
                <td colspan="3">
                    @if($caso->recibio_apoyos_pie)
                        Sí. Detalle: {{ $caso->detalle_apoyos_pie }}
                    @else No @endif
                </td>
            </tr>
            <tr>
                <th>Repitencia Escolar</th>
                <td colspan="3">
                    @if($caso->repitio_curso)
                        Sí. Motivo: {{ $caso->motivo_repeticion }}
                    @else No @endif
                </td>
            </tr>
        </table>
    </div>

    {{-- 5. ENTREVISTA Y CONTEXTO --}}
    <div class="section">
        <div class="section-title">4. Entrevista: Características e Intereses</div>
        <table>
            <tr>
                <th>Características e Intereses</th>
                <td class="long-text">
                    {{ $caso->caracteristicas_intereses ?? 'Sin información registrada en la entrevista.' }}
                </td>
            </tr>
            @if($caso->trabaja)
            <tr>
                <th>Situación Laboral</th>
                <td>Trabaja en <strong>{{ $caso->empresa }}</strong> como <strong>{{ $caso->cargo }}</strong>.</td>
            </tr>
            @endif
        </table>
    </div>

    {{-- 6. SOLICITUDES DEL ESTUDIANTE (ANAMNESIS) --}}
    <div class="section">
        <div class="section-title">5. Solicitudes del Estudiante (Anamnesis)</div>
        @if(is_array($caso->ajustes_propuestos) && count($caso->ajustes_propuestos) > 0)
            <ul>
                @foreach($caso->ajustes_propuestos as $solicitud)
                    @if(!empty($solicitud)) <li>{{ $solicitud }}</li> @endif
                @endforeach
            </ul>
        @else
            <p style="padding-left: 10px; font-style: italic;">No se registraron solicitudes específicas.</p>
        @endif
    </div>

    {{-- 7. RESOLUCIÓN TÉCNICA (TABLA DE AJUSTES) --}}
    @if(is_array($caso->ajustes_ctp) && count($caso->ajustes_ctp) > 0)
    <div class="section">
        <div class="section-title">6. Resolución de Ajustes Razonables (Oficial)</div>
        <table>
            <thead>
                <tr style="background-color: #eee;">
                    <th style="width: 40%;">Recurso / Ajuste Propuesto</th>
                    <th style="width: 15%;">Resolución</th>
                    <th style="width: 45%;">Observación Dirección</th>
                </tr>
            </thead>
            <tbody>
                @foreach($caso->ajustes_ctp as $index => $ajuste)
                    @php 
                        // Buscamos la evaluación correspondiente a este índice
                        $eval = $caso->evaluacion_director[$index] ?? null; 
                        $estado = $eval['decision'] ?? 'Pendiente';
                    @endphp
                    <tr>
                        <td>{{ $ajuste }}</td>
                        <td>
                            @if($estado == 'Aceptado') <span class="status-accepted">APROBADO</span>
                            @elseif($estado == 'Rechazado') <span class="status-rejected">RECHAZADO</span>
                            @else <span class="status-pending">{{ $estado }}</span> @endif
                        </td>
                        <td>{{ $eval['comentario'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="section">
        <div class="section-title">6. Resolución de Ajustes Razonables (Oficial)</div>
        <p style="padding: 10px;">No se registraron ajustes técnicos en este caso.</p>
    </div>
    @endif

    {{-- 8. OBSERVACIONES FINALES --}}
    <div class="section">
        <div class="section-title">7. Observaciones Finales de Dirección</div>
        <div style="border: 1px solid #ccc; padding: 10px; min-height: 40px; background: #f9f9f9;">
            {{ $caso->motivo_decision ?? 'Sin observaciones generales.' }}
        </div>
    </div>

    {{-- 9. BITÁCORA DOCENTE (NUEVO) --}}
    <div class="section" style="page-break-inside: avoid;">
        <div class="section-title">8. Bitácora de Seguimiento Docente</div>
        
        @if($caso->seguimientos->count() > 0)
            <table>
                <thead>
                    <tr style="background-color: #eee;">
                        <th style="width: 25%;">Fecha / Docente</th>
                        <th style="width: 75%;">Observación Registrada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($caso->seguimientos as $seguimiento)
                        <tr>
                            <td style="font-size: 10px;">
                                <strong>{{ $seguimiento->created_at->format('d/m/Y') }}</strong><br>
                                {{ $seguimiento->docente->name ?? 'Docente' }}
                            </td>
                            <td>{{ $seguimiento->comentario }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="padding: 10px; font-style: italic; border: 1px solid #ccc; background: #f9f9f9;">
                No se han registrado observaciones de seguimiento por parte de los docentes hasta la fecha.
            </p>
        @endif
    </div>


    <div class="footer">
        Documento generado el {{ $fecha_impresion }}. <br>
        La información contenida es confidencial según la Ley 19.628 sobre Protección de la Vida Privada.
    </div>

</body>
</html>