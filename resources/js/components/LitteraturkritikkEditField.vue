<template>
    <tr>
        <td style="width: 150px;" valign="top">
            {{ label }}
        </td>
        <td>
            <component
                :is="definition.type"
                :name="definition.key"
                :value="value"
                :definition="definition"
                @value="$emit('value', $event)"
            ></component>
        </td>
    </tr>
</template>

<script>
    import * as components from './input'

    export default {
        name: "LitteraturkritikkEditField",
        components: {
            ...components,
        },
        props: [
            'label',
            'value',
            'definition',
        ]
    }
</script>

<style scoped>

</style>