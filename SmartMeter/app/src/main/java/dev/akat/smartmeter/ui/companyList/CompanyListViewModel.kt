package dev.akat.smartmeter.ui.companyList

import android.util.Log
import androidx.lifecycle.LiveData
import androidx.lifecycle.MutableLiveData
import androidx.lifecycle.ViewModel
import androidx.lifecycle.viewModelScope
import dev.akat.smartmeter.model.Company
import dev.akat.smartmeter.repository.MeterRepository
import dev.akat.smartmeter.utils.Event
import kotlinx.coroutines.launch
import javax.inject.Inject

class CompanyListViewModel @Inject constructor(private val meterRepository: MeterRepository) :
    ViewModel() {

    private val _isFetched = MutableLiveData<Event<Boolean>>()
    private val _errorMessage = MutableLiveData<Event<String>>()

    val isFetched: LiveData<Event<Boolean>> get() = _isFetched
    val errorMessage: LiveData<Event<String>> get() = _errorMessage
    val companyList: MutableLiveData<List<Company>> = MutableLiveData()

    init {
        viewModelScope.launch {
            try {
                val response = meterRepository.getCompanyList()
                if (response.ok) companyList.postValue(response.data)
                else Log.d("AccountList", response.error)
            } catch (e: Exception) {
            }
        }
    }

    fun updateAccountQuery(accountName: String, companyId: Long) {
        viewModelScope.launch {
            try {
                val response = meterRepository.updateAccountQuery(
                    meterRepository.getAuthId(),
                    accountName,
                    companyId
                )

                _isFetched.postValue(Event(response.ok))
                _errorMessage.postValue(Event(response.error))
            } catch (e: Exception) {
            }
        }
    }

}