<template>
    <div style="display: flex;">
        <div style="flex: 1 1 auto; padding:6px;">
            <vue-slider :value="valueAsArray"
                        :adsorb="false"
                        :duration="0"
                        :enable-cross="false"
                        :min="definition.options.minValue"
                        :max="definition.options.maxValue"
                        :useKeyboard="true"
                        tooltip="none"
                        @change="onChange($event)"
            ></vue-slider>
        </div>
        <div style="padding:4px;">
            {{ value }}
            <input type="hidden"
                   :name="name"
                   :value="value">
        </div>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'

    export default {
        name: "DateRangeInput",

        components: {
            VueSlider,
        },

        props: {
            name: String,
            definition: Object,
            value: String,
        },

        computed: {
            valueAsArray() {
                let val = this.value.split('-');
                if (val.length !== 2 || !val[0].match(/-?[0-9]{1,4}/) || !val[1].match(/-?[0-9]{1,4}/) ) {
                    // Return default value
                    return [this.definition.options.minValue, this.definition.options.maxValue];
                }
                return [parseInt(val[0]), parseInt(val[1])];
            },
        },

        mounted() {
            // We might have inherited a value from another component, so validate
            // and update it just in case.
            this.$emit('value', `${this.valueAsArray[0]}-${this.valueAsArray[1]}`);
        },

        methods: {
            onChange(newValue) {
                this.$emit('value', `${newValue[0]}-${newValue[1]}`);
            }
        }
    }
</script>
