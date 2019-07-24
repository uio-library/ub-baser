<template>
    <tr>
        <td style="width: 150px;" valign="top">
            {{ schema.label }}
        </td>
        <td>
            <component
                :is="schema.type"
                :name="schema.key"
                :value="value"
                :schema="schema"
                @value="$emit('value', $event)"
            ></component>
        </td>
    </tr>
</template>

<script>
    import * as components from './input'

    export default {
        name: "edit-field",
        components: {
            ...components,
        },
        props: [
            'schema',
            'value',
        ]
    }
</script>

<style scoped>

</style>