import React from 'react';
import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import '../css/app.css';
const pages=import.meta.glob('./Pages/*.tsx');
createInertiaApp({title:title=>`${title} · School Management`,resolve:async name=>{const page=await pages[`./Pages/${name}.tsx`]();return page as any;},setup({el,App,props}){createRoot(el).render(<App {...props}/>);}});
