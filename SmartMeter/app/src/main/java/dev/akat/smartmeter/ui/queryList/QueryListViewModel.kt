package dev.akat.smartmeter.ui.queryList

import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.model.QueryInfo
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import kotlinx.coroutines.launch
import javax.inject.Inject

class QueryListViewModel @Inject constructor(private val meterRepository: MeterRepository) :
    ViewModel() {

    private val _isLoading = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()

    val isLoading: LiveData<Event<Boolean>> get() = _isLoading
    val errorMessage: LiveData<Event<String>> get() = _errorMessage
    val queryList: MutableLiveData<List<QueryInfo>> = MutableLiveData()

    init {
        loadQueries()
    }

    fun loadQueries() {
        viewModelScope.launch {
            try {
                _isLoading.postValue(Event(true))

                val response = meterRepository.getQueryAccounts(meterRepository.getAuthId())
                if (response.ok) queryList.postValue(response.data)
                else Log.d("QueryList", response.error)

                _isLoading.postValue(Event(false))
            } catch (e: Exception) {
                _isLoading.postValue(Event(false))
            }
        }
    }

}