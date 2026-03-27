

# AI_JOURNEY

Este archivo describe cómo utilicé IA durante el desarrollo de esta prueba técnica.

La intención no fue delegar el proyecto a una herramienta, sino usarla para acelerar iteraciones, contrastar enfoques y mejorar partes concretas del trabajo. Las decisiones finales, la validación del código y los ajustes al requerimiento fueron manuales.

## Objetivo del uso de IA

Usé IA como apoyo en tareas donde podía ahorrar tiempo sin perder control técnico. Principalmente para:

- generar borradores iniciales
- comparar opciones de implementación
- revisar consultas y refactors
- mejorar redacción técnica
- detectar puntos débiles en legibilidad o cumplimiento del reto

La prioridad fue siempre resolver bien el problema, no automatizar por automatizar.

## Herramientas utilizadas

Durante el desarrollo utilicé GitHub Copilot desde la consola de VS Code para discutir implementación, revisar estructura del código y redactar documentación.

Su papel fue proponer alternativas y acelerar trabajo mecánico. 

## Estrategia de prompting

Los prompts se redactaron con contexto suficiente para evitar respuestas genéricas. En general incluían:

- stack del proyecto
- restricciones del reto
- alcance real de la funcionalidad
- limitaciones explícitas, por ejemplo no inventar features o no resolver en memoria lo que debía ir en SQL

Esto fue importante porque, sin contexto claro, la IA tendía a sugerir soluciones demasiado amplias o poco alineadas con el ejercicio.

## Tareas en las que ayudó la IA

La IA fue útil en estas partes del trabajo:

- proponer borradores de código para algunas piezas del proyecto
- revisar la estructura de consultas con Eloquent
- sugerir mejoras en el dashboard
- ayudar a detectar posibles N+1
- refinar redacción de README y documentación complementaria
- ordenar ideas para presentar decisiones técnicas con más claridad

En todos los casos, el resultado final se revisó y ajustó antes de integrarlo.

## Tareas donde fue necesaria validación humana

### Cumplimiento del requerimiento
No todas las propuestas iniciales respetaban exactamente el alcance del reto. Fue necesario revisar constantemente si una sugerencia resolvía el problema pedido o si agregaba complejidad innecesaria. Se buscaba un esqueleto firme de proyecto al inicio, posteriormente pudimos implementar algo de complejidad en las vistas y bdd principalmente.

### Rendimiento de consultas
Una parte importante fue comprobar que los filtros relevantes se hicieran en SQL y no sobre colecciones ya cargadas. Esto fue especialmente importante en el dashboard y en el comando Artisan.

### Lógica de fechas y estados
La clasificación entre pedidos por enviar y retrasados necesitaba ajustarse al modelo real del proyecto. En este caso, “retrasado” no se trató como un estado persistido independiente, sino como un pedido pendiente con fecha vencida.

### Flujo OAuth
También fue necesario revisar que la autenticación cumpliera con la restricción de no permitir registro manual y que el uso de Socialite encajara con ese comportamiento.

## Ejemplos de prompts utilizados

### Ejemplo 1: consultas del dashboard
> Estoy construyendo un dashboard en Laravel para una prueba técnica de logística e-commerce. Necesito obtener pedidos agrupados en estas cuatro categorías exactas: por enviar, retrasados, entregados y cancelados. Quiero resolverlo con Eloquent usando Local Scopes en el modelo Pedido. Obligatoriamente debe cumplir: paginar desde base de datos, no en memoria, mantener consultas claras y legibles, no usar get() antes de paginar o filtrar, no mover la lógica de negocio a la vista. Supón que cada pedido pertenece a un cliente y puede tener varios productos, tienes que devolver: propuesta de scopes, ejemplo de consulta desde controlador, explicación breve de por qué esta solución evita N+1 y escala mejor. Si detectas ambigüedad en la definición de alguna categoría, indícalo antes de escribir el código.

### Ejemplo 2: comando Artisan
> Necesito un comando Artisan en Laravel para procesar pedidos bajo reglas específicas de negocio: deben considerarse únicamente los pedidos con estado pendiente, cuya fecha_entrega sea mañana evaluando solo la fecha, y que además estén relacionados con el producto de id 5; la acción a ejecutar consiste en incrementar el total del pedido en un 10%. La solución debe cumplir estas restricciones obligatorias: filtrar en SQL y no en memoria, no cargar todos los pedidos con all() o get() para después filtrarlos, usar Eloquent de forma clara mediante métodos como whereDate y whereHas, considerar cómo evitar reprocesamiento accidental si el comando se ejecuta más de una vez, y mantener el código legible y alineado a buenas prácticas de Laravel. Devuélveme el método handle() completo, una explicación breve de por qué la consulta es eficiente y las advertencias relevantes.

### Ejemplo 3: resolución 
> Ya tengo avanzada la prueba técnica en Laravel y necesito apoyo para refinar la resolución del proyecto. En este punto ya existe autenticación con Socialite, estructura base de modelos y relaciones, dashboard de pedidos y comando Artisan. Quiero revisar especialmente si la lógica de consultas del dashboard, el uso de Local Scopes, la carga de relaciones con eager loading, la paginación y el procesamiento de pedidos en consola están resueltos de forma correcta y eficiente. Busco una revisión centrada en implementación real, evitando filtros en memoria, ambigüedades en reglas de negocio y complejidad innecesaria.

## Ejemplos de sugerencias corregidas o rechazadas

Una parte importante del uso de IA fue revisar qué propuestas sí convenía conservar y cuáles no.

### 1. Reemplazar filtros en memoria por filtros en SQL
En algunas propuestas iniciales aparecían enfoques basados en cargar registros y luego filtrarlos en PHP. Eso no era consistente con el objetivo del reto.

La corrección fue mover el filtrado a la consulta, tanto en el dashboard como en el comando Artisan, usando Eloquent y `whereHas` cuando correspondía.

### 2. Ajustar la lógica de retrasados
Una sugerencia inicial llevaba a usar “retrasado” como estado almacenado. Eso no encajaba bien con el modelo existente.

La corrección fue tratar “retrasados” como una categoría derivada: pedidos con estado `pendiente` cuya fecha de entrega ya venció.

### 3. Afinar el comando Artisan
La primera versión resolvía la lógica principal, pero todavía permitía reprocesar pedidos en ejecuciones repetidas.

La corrección fue blindar el comando para que no aplicara el mismo cargo dos veces sobre el mismo pedido.

### 4. Corregir documentación exagerada
En la parte documental, algunas propuestas usaban frases demasiado amplias o poco precisas.

La corrección fue reescribir esos textos para que describieran solo lo que realmente implementa el proyecto: autenticación OAuth con Socialite, dashboard agrupado, scopes, eager loading, paginación real y un comando Artisan eficiente.

## Consideraciones de seguridad

Durante el uso de IA se siguieron criterios básicos de exposición mínima:

- no compartí archivos `.env`
- no expuse secretos ni credenciales OAuth
- no incluí tokens reales en prompts
- limité el contexto compartido a lo necesario para recibir ayuda útil
- evité copiar configuraciones sensibles fuera del entorno local

Esto permitió usar asistencia sin comprometer información privada del proyecto.

## Aprendizajes obtenidos

El principal aprendizaje fue que la IA aporta más valor cuando se usa con contexto claro y límites concretos. Mientras más específico fue el prompt, más útil fue la respuesta.

También confirmé que la IA funciona bien para acelerar borradores, comparar alternativas y pulir documentación, pero no reemplaza validación técnica. En este proyecto, el criterio humano siguió siendo necesario para revisar:

- si la solución cumplía exactamente el reto
- si la consulta era razonable
- si la lógica representaba bien el problema
- si la documentación era precisa

Otro aprendizaje importante fue no aceptar propuestas solo porque suenan correctas. Lo útil fue tratarlas como hipótesis de trabajo, no como resultado final.

## Conclusión

En este proyecto, la IA fue una herramienta de apoyo, no un sustituto de criterio técnico.

Su aporte estuvo en acelerar iteraciones y ayudar a contrastar enfoques. Aun así, cada decisión importante se revisó manualmente para asegurar que el resultado final fuera claro, coherente con el requerimiento y técnicamente defendible.
