<template>
    <button type="submit" v-if="type === 'submit'" :disabled="loading || disabled" :title="tooltip"
            class="d-flex align-items-center justify-content-center">
        <slot v-if="loading === false"></slot>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-else>
            <span class="sr-only">Loading...</span>
        </span>
    </button>
    <button type="button" v-else-if="type === 'button'" :disabled="loading || disabled" @click.stop.prevent="click"
            :title="tooltip" class="d-flex align-items-center justify-content-center">
        <slot v-if="loading === false"></slot>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-else>
            <span class="sr-only">Loading...</span>
        </span>
    </button>
    <a :href="href" v-else-if="type === 'link'" :title="tooltip"
       class="d-flex align-items-center justify-content-center">
        <slot v-if="loading === false"></slot>
        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true" v-else>
            <span class="sr-only">Loading...</span>
        </span>
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
