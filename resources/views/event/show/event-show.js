import EventForm from "../../../js/components/Forms/EventForm";
import EventChoicesForm from "../../../js/components/Forms/EventChoicesForm";

const app = new Vue({
    el: '#event-show-page',
    store: Store,
    components: {
        EventForm,
        EventChoicesForm,
    },
})
