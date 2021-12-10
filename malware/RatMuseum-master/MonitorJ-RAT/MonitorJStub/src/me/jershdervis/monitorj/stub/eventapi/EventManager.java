package me.jershdervis.monitorj.stub.eventapi;

import java.lang.reflect.Method;
import java.util.HashMap;
import java.util.Iterator;
import java.util.Map;

/**
 * @author DarkMagician6
 */
public final class EventManager {

	private static final Map<Class<? extends Event>, FlexibleArray<MethodData>> REGISTRY_MAP = new HashMap<Class<? extends Event>, FlexibleArray<MethodData>>();

	private EventManager() {
	}

    public static void register(Object object) {
        for (final Method method : object.getClass().getDeclaredMethods()) {
            if (isMethodBad(method)) {
                continue;
            }

            register(method, object);
        }
    }

    public static void register(Object object, Class<? extends Event> eventClass) {
        for (final Method method : object.getClass().getDeclaredMethods()) {
            if (isMethodBad(method, eventClass)) {
                continue;
            }

            register(method, object);
        }
    }

    public static void unregister(Object object) {
        for (final FlexibleArray<MethodData> dataList : REGISTRY_MAP.values()) {
            for (final MethodData data : dataList) {
                if (data.source.equals(object)) {
                    dataList.remove(data);
                }
            }
        }

        cleanMap(true);
    }

    public static void unregister(Object object, Class<? extends Event> eventClass) {
        if (REGISTRY_MAP.containsKey(eventClass)) {
            for (final MethodData data : REGISTRY_MAP.get(eventClass)) {
                if (data.source.equals(object)) {
                	REGISTRY_MAP.get(eventClass).remove(data);
                }
            }

            cleanMap(true);
        }
    }

    private static void register(Method method, Object object) {
    	Class<? extends Event> indexClass = (Class<? extends Event>) method.getParameterTypes()[0];
    	//New MethodData from the Method we are registering.
    	final MethodData data = new MethodData(object, method, method.getAnnotation(EventTarget.class).value());
    	
    	//Set's the method to accessible so that we can also invoke it if it's protected or private.
    	if (!data.target.isAccessible()) {
    		data.target.setAccessible(true);
    	}
	
    	if (REGISTRY_MAP.containsKey(indexClass)) {
    		if (!REGISTRY_MAP.get(indexClass).contains(data)) {
    			REGISTRY_MAP.get(indexClass).add(data);
    			sortListValue(indexClass);
    		}
    	} else {
    		REGISTRY_MAP.put(indexClass, new FlexibleArray<MethodData>() {
    			//Eclipse was bitching about a serialVersionUID.
    			private static final long serialVersionUID = 666L; {
    				add(data);
    			}
    		});
    	}
    }

    public static void removeEntry(Class<? extends Event> indexClass) {
        Iterator<Map.Entry<Class<? extends Event>, FlexibleArray<MethodData>>> mapIterator = REGISTRY_MAP.entrySet().iterator();

        while (mapIterator.hasNext()) {
            if (mapIterator.next().getKey().equals(indexClass)) {
                mapIterator.remove();
                break;
            }
        }
    }

    public static void cleanMap(boolean onlyEmptyEntries) {
        Iterator<Map.Entry<Class<? extends Event>, FlexibleArray<MethodData>>> mapIterator = REGISTRY_MAP.entrySet().iterator();

        while (mapIterator.hasNext()) {
            if (!onlyEmptyEntries || mapIterator.next().getValue().isEmpty()) {
                mapIterator.remove();
            }
        }
    }

    private static void sortListValue(Class<? extends Event> indexClass) {
    	FlexibleArray<MethodData> sortedList = new FlexibleArray<MethodData>();

        for (final byte priority : Priority.VALUE_ARRAY) {
            for (final MethodData data : REGISTRY_MAP.get(indexClass)) {
                if (data.priority == priority) {
                    sortedList.add(data);
                }
            }
        }

        //Overwriting the existing entry.
        REGISTRY_MAP.put(indexClass, sortedList);
    }

    private static boolean isMethodBad(Method method) {
        return method.getParameterTypes().length != 1 || !method.isAnnotationPresent(EventTarget.class);
    }

    private static boolean isMethodBad(Method method, Class<? extends Event> eventClass) {
        return isMethodBad(method) || !method.getParameterTypes()[0].equals(eventClass);
    }
    
    public static FlexibleArray<MethodData> get(Class<? extends Event> clazz) {
    	return REGISTRY_MAP.get(clazz);
    }

}
