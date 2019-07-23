<template>
    <tr>
        <td>
            {{ label }}
        </td>
        <td>
            <input type="text" :name="field" :value="value">
        </td>
        <td>
            {{ columnDefinition }}
        </td>
    </tr>
</template>

<script>
    export default {
        name: "LitteraturkritikkFieldEditor",
        props: ['field', 'label', 'value', 'columnDefinition']
    }
</script>

<style scoped>

</style>