import './bootstrap';
import clock from './support/clock'

import Alpine from 'alpinejs'
import intersect from '@alpinejs/intersect'

import 'bootstrap-icons/font/bootstrap-icons.css';

Alpine.plugin(intersect)

window.Alpine = Alpine

Alpine.data('clock', clock)

Alpine.start()
