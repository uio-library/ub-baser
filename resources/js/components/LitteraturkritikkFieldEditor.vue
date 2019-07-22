<template>
    <tr>
        <td>
            {{ label }}
        </td>
        <td>
            <input type="text" :name="field" :value="value">
        </td>
        <td>
            {{ column }}
        </td>
    </tr>
</template>

<script>
    export default {
        name: "LitteraturkritikkFieldEditor",
        props: ['field', 'label', 'value', 'column']
    }
</script>

<style scoped>

</style>