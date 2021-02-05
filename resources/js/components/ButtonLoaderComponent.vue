<template>
    <button type="submit" v-if="type === 'submit'" :disabled="loading || disabled" :title="tooltip"
            class="">
        <slot v-if="loading === false"></slot>
        <loader v-else></loader>
    </button>
    <button type="button" v-else-if="type === 'button'" :disabled="loading || disabled" @click.stop.prevent="click"
            :title="tooltip" class="">
        <slot v-if="loading === false"></slot>
        <loader v-else></loader>
    </button>
    <a :href="href" v-else-if="type === 'link'" :title="tooltip"
       class="">
        <slot v-if="loading === false"></slot>
        <loader v-else></loader>
    </a>
</template>

<script>
export default {
    props: {
        disabled: {
            type: Boolean,
            default: false,
        },
        loading: {
            type: Boolean,
            default: false,
        },
        href: String,
        type: String,
        on_click: {
            type: Function,
            default: function () {
            }
        },
        tooltip: String,
    },
    mounted: function () {
        if (this.tooltip) {
            $(this.$el).tooltip();
        }
    },
    methods: {
        click: function () {
            if (this.tooltip) {
                $(this.$el).tooltip('hide');
            }
            if (this.on_click && !this.loading && !this.disabled) {
                this.on_click();
            }
        }
    },
    beforeDestroy: function () {
        if (this.tooltip) {
            $(this.$el).tooltip('dispose');
        }
    }
}
</script>
