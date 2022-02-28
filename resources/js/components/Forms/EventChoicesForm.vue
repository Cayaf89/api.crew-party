<template>
    <div>
        <div class="row">
            <div class="col-3">
                <div class="d-flex">
                    <div class="input-group">
                        <date-picker v-model="newDate" mode="date" :model-config="modelConfig" trim-weeks>
                            <template v-slot="{ inputValue, inputEvents }">
                                <input
                                    class="form-control"
                                    :value="inputValue"
                                    v-on="inputEvents"
                                />
                            </template>
                        </date-picker>

                        <div class="input-group-append">
                            <button type="button" class="btn btn-primary"
                                    @click.prevent="saveNewDate">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <ul>
                    <li v-for="eventChoice in eventChoices">
                        {{ eventChoice.date }}
                        <i v-if="eventChoice.is_user_choice" class="fas fa-check-circle ml-3 text-green"></i>
                        <button v-else type="button" class="btn btn-primary ml-3"><i class="fas fa-plus"></i></button>
                    </li>
                </ul>
            </div>
            <div class="col-6">
                <calendar :attributes="calendarAttributes" is-expanded trim-weeks></calendar>
            </div>
        </div>
    </div>
</template>

<script>
import Calendar from 'v-calendar/lib/components/calendar.umd'
import DatePicker from 'v-calendar/lib/components/date-picker.umd'
import { DateTime } from 'luxon';

export default {
    name: "EventChoicesForm",
    components: {
        Calendar,
        DatePicker,
    },
    props: {
        eventId: Number
    },
    data: function () {
        return {
            eventChoices: [],
            newDate: '',
            modelConfig: {
                type: 'string',
                mask: 'DD/MM/YYYY',
            },
        }
    },
    mounted() {
        this.getEventChoices();
    },
    methods: {
        saveNewDate: function () {
            if (this.$props.eventId) {
                axios.post('/api/event/' + this.$props.eventId + '/choice', {
                    date: this.newDate
                })
                    .then(res => {
                        const eventChoiceModified = this.eventChoices.findIndex(element => element.id === res.data.id);
                        if (eventChoiceModified !== -1) {
                            this.eventChoices[eventChoiceModified] = res.data;
                        }
                        else {
                            this.eventChoices.push(res.data);
                        }
                        this.newDate = '';
                    })
                    .catch(error => {
                        if (error?.response?.data?.error) {
                            toastr.error(manage_errors(error.response.data.error));
                        }
                    })
            }
        },

        getEventChoices: function () {
            axios.get('/api/event/' + this.$props.eventId + '/choices')
                .then(res => {
                    this.eventChoices = res.data.data;
                })
                .catch(error => {
                    if (error?.response?.data?.error) {
                        toastr.error(manage_errors(error.response.data.error));
                    }
                })
        },
    },
    computed: {
        calendarAttributes: function () {
            return [
                ...this.eventChoices.map(eventChoice => ({
                    dates: DateTime.fromFormat(eventChoice.date, 'dd/MM/yyyy').toJSDate(),
                    dot: eventChoice.is_user_choice ? 'red' : 'blue',
                    popover: {
                        label: 'Nombre de personnes : ' + eventChoice.countUsers,
                    },
                })),
            ];
        }
    }
}
</script>

<style lang="scss" scoped>

</style>
