package me.jershdervis.monitorj.stub.eventapi;

import java.util.Iterator;

/**
 * 
 * @author DarkMagician6
 *
 * @param <T>
 * 		Type of Object you want to store.
 */
public class FlexibleArray<T> implements Iterable<T> {
	
	/**
	 * Array containing all stored instances of <T>.
	 */
	private T[] elements;
	
	/**
	 * Creates a new instance of the FlexibelArray class with a preset array containing <T> instances.
	 * 
	 * @param array
	 * 		Array containing <T> instances.
	 */
	public FlexibleArray(T[] array) {
		this.elements = array;
	}
	
	/**
	 * Creates a new empty instance of the FlexibalArray class.
	 */
	public FlexibleArray() {
		this.elements = (T[]) new Object[0];
	}
	
	/**
	 * Adds a new instance of <T> to the FlexibleArray.
	 * 
	 * @param t
	 * 		Instance of <T> you want to add.
	 */
	public void add(T t) {
		if (t != null) {
			T[] array = (T[]) new Object[size() + 1];
			
			for (int i = 0; i < array.length; i++) {
				if (i < size()) {
					array[i] = this.get(i);
				} else {
					array[i] = t;
				}
			}
			
			this.set(array);
		}
	}
	
	/**
	 * Removes an instance of <T> from the FlexibleArray.
	 * 
	 * @param t
	 * 		Instance of <T> you want to remove.
	 */
	public void remove(T t) {
		if (this.contains(t)) {
			T[] array = (T[]) new Object[size() - 1];
			boolean b = true;
			
			for (int i = 0; i < size(); i++) {
				if (b && get(i).equals(t)) {
					b = false;
				} else {
					array[b ? i : i - 1] = this.get(i);
				}
			}
			
			this.set(array);
		}
	}
	
	/**
	 * Checks if the FlexibleArray contains the specified instance of <T>.
	 * 
	 * @param t
	 * 		Instance of <T> that you want to know of if it's in the FlexibleArray.
	 * @return
	 * 		True if the FlexibleArray contains "t".
	 */
	public boolean contains(T t) {
		for (T entry : this.array()) {
			if (entry.equals(t)) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * Sets the FlexibleArray to a new Array of <T>'s.
	 * 
	 * @param array
	 * 		The new Array of <T>'s you want to set the FlexibleArray to.
	 */
	private void set(T[] array) {
		this.elements = array;
	}
	
	/**
	 * Clears the FlexibleArray.
	 */
	public void clear() {
		this.elements = (T[]) new Object[0];
	}
	
	/**
	 * @param index
	 * 		The index of which <T> instance you want to get.
	 * @return
	 * 		Instance of <T> stored at the location of the index.
	 */
	public T get(int index) {
		return this.array()[index];
	}
	
	/**
	 * @return
	 * 		The amount of instances of <T> that are stored.
	 */
	public int size() {
		return this.array().length;
	}
	
	/**
	 * @return
	 * 		Array containing all instances of <T>.
	 */
	public T[] array() {
		return elements;
	}
	
	/**
	 * @return
	 * 		True if the FlexibleArray doesn't contain any Objects.
	 */
	public boolean isEmpty() {
		return this.size() == 0;
	}

	/**
	 * 
	 */
	@Override
	public Iterator<T> iterator() {
        return new Iterator<T>() {
        	
            private int index = 0;

            @Override
            public boolean hasNext() {
                return index < FlexibleArray.this.size() && FlexibleArray.this.get(index) != null;
            }

            @Override
            public T next() {
                return FlexibleArray.this.get(index++);
            }

            @Override
            public void remove() {
            	FlexibleArray.this.remove(get(index));
            }
        };
	}

}